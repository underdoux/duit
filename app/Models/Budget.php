<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Budget extends Model
{
    protected $fillable = [
        'name',
        'amount',
        'period',
        'start_date',
        'end_date',
        'notes',
        'user_id',
        'category_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    public function categories()
    {
        return $this->hasMany(BudgetCategory::class);
    }

    public function getProgress()
    {
        $spent = ExpenseTransaction::where('category_id', $this->category_id)
            ->whereBetween('date', [
                $this->start_date,
                $this->end_date ?? now()
            ])
            ->sum('amount');

        return [
            'spent' => $spent,
            'total' => $this->amount,
            'remaining' => $this->amount - $spent,
            'percentage' => $this->amount > 0 ? ($spent / $this->amount) * 100 : 0,
            'status' => $this->getStatus($spent)
        ];
    }

    private function getStatus($spent)
    {
        $percentage = $this->amount > 0 ? ($spent / $this->amount) * 100 : 0;
        
        if ($percentage >= 100) {
            return 'exceeded';
        } elseif ($percentage >= 80) {
            return 'warning';
        } elseif ($percentage >= 50) {
            return 'moderate';
        } else {
            return 'good';
        }
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', now());
        });
    }

    public function scopeThisMonth($query)
    {
        $now = Carbon::now();
        return $query->whereMonth('start_date', '<=', $now)
                    ->where(function ($q) use ($now) {
                        $q->whereMonth('end_date', '>=', $now)
                          ->orWhereNull('end_date');
                    });
    }

    public function getRemainingDaysAttribute()
    {
        if (!$this->end_date) {
            return null;
        }

        return max(0, now()->diffInDays($this->end_date, false));
    }

    public function getDailyTargetAttribute()
    {
        if (!$this->end_date) {
            return null;
        }

        $remainingAmount = $this->amount - $this->getProgress()['spent'];
        $remainingDays = $this->remaining_days;

        return $remainingDays > 0 ? $remainingAmount / $remainingDays : 0;
    }
}
