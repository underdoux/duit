<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FinancialGoal extends Model
{
    protected $fillable = [
        'name',
        'target_amount',
        'current_amount',
        'target_date',
        'status',
        'description',
        'priority',
        'user_id',
        'account_id',
    ];

    protected $casts = [
        'target_date' => 'date',
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function transactions()
    {
        return $this->hasMany(GoalTransaction::class, 'goal_id');
    }

    public function getProgress()
    {
        return [
            'current' => $this->current_amount,
            'target' => $this->target_amount,
            'remaining' => $this->target_amount - $this->current_amount,
            'percentage' => $this->target_amount > 0 ? 
                ($this->current_amount / $this->target_amount) * 100 : 0,
            'status' => $this->getStatus()
        ];
    }

    public function getStatus()
    {
        if ($this->status === 'completed') {
            return 'completed';
        }

        if ($this->status === 'cancelled') {
            return 'cancelled';
        }

        $percentage = $this->target_amount > 0 ? 
            ($this->current_amount / $this->target_amount) * 100 : 0;

        if ($percentage >= 100) {
            return 'achieved';
        }

        if ($this->isOverdue()) {
            return 'overdue';
        }

        if ($this->isAtRisk()) {
            return 'at_risk';
        }

        return 'in_progress';
    }

    public function isOverdue()
    {
        return $this->target_date && $this->target_date->isPast() && 
            $this->current_amount < $this->target_amount;
    }

    public function isAtRisk()
    {
        if (!$this->target_date) {
            return false;
        }

        $daysLeft = now()->diffInDays($this->target_date, false);
        $remainingAmount = $this->target_amount - $this->current_amount;
        $requiredDailyAmount = $daysLeft > 0 ? $remainingAmount / $daysLeft : 0;
        $averageDailyContribution = $this->getAverageDailyContribution();

        return $averageDailyContribution < $requiredDailyAmount;
    }

    public function getAverageDailyContribution()
    {
        $startDate = $this->transactions()->min('date') ?? $this->created_at->toDateString();
        $daysSinceStart = max(1, now()->diffInDays(Carbon::parse($startDate)));
        
        return $this->current_amount / $daysSinceStart;
    }

    public function getRemainingDaysAttribute()
    {
        if (!$this->target_date) {
            return null;
        }

        return max(0, now()->diffInDays($this->target_date, false));
    }

    public function getRequiredDailyAmountAttribute()
    {
        if (!$this->target_date || $this->status !== 'in_progress') {
            return null;
        }

        $remainingAmount = $this->target_amount - $this->current_amount;
        $remainingDays = $this->remaining_days;

        return $remainingDays > 0 ? $remainingAmount / $remainingDays : 0;
    }

    public function addTransaction($amount, $type = 'deposit', $notes = null)
    {
        $transaction = $this->transactions()->create([
            'amount' => $amount,
            'type' => $type,
            'date' => now(),
            'notes' => $notes,
            'user_id' => $this->user_id
        ]);

        $this->updateCurrentAmount();

        return $transaction;
    }

    public function updateCurrentAmount()
    {
        $deposits = $this->transactions()->where('type', 'deposit')->sum('amount');
        $withdrawals = $this->transactions()->where('type', 'withdrawal')->sum('amount');
        
        $this->current_amount = $deposits - $withdrawals;
        
        if ($this->current_amount >= $this->target_amount) {
            $this->status = 'completed';
        }
        
        $this->save();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }
}
