<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoalTransaction extends Model
{
    protected $fillable = [
        'amount',
        'type',
        'date',
        'notes',
        'goal_id',
        'user_id',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function goal()
    {
        return $this->belongsTo(FinancialGoal::class, 'goal_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::created(function ($transaction) {
            $transaction->goal->updateCurrentAmount();
        });

        static::updated(function ($transaction) {
            $transaction->goal->updateCurrentAmount();
        });

        static::deleted(function ($transaction) {
            $transaction->goal->updateCurrentAmount();
        });
    }

    public function getFormattedAmountAttribute()
    {
        $prefix = $this->type === 'withdrawal' ? '-' : '';
        return $prefix . number_format($this->amount, 2);
    }

    public function getFormattedDateAttribute()
    {
        return $this->date->format('M d, Y');
    }

    public function getImpactOnGoalAttribute()
    {
        $progress = $this->goal->getProgress();
        $previousAmount = $this->type === 'deposit' ? 
            $this->goal->current_amount - $this->amount : 
            $this->goal->current_amount + $this->amount;
        
        $previousPercentage = ($previousAmount / $this->goal->target_amount) * 100;
        $currentPercentage = $progress['percentage'];

        return [
            'change' => $currentPercentage - $previousPercentage,
            'previous_percentage' => $previousPercentage,
            'current_percentage' => $currentPercentage
        ];
    }

    public function scopeDeposits($query)
    {
        return $query->where('type', 'deposit');
    }

    public function scopeWithdrawals($query)
    {
        return $query->where('type', 'withdrawal');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month)
                    ->whereYear('date', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('date', now()->year);
    }

    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
}
