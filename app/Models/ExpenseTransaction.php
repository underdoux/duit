<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ExpenseTransaction extends Model
{
    protected $fillable = [
        'amount',
        'date',
        'description',
        'reference_number',
        'status',
        'notes',
        'user_id',
        'account_id',
        'category_id',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    protected static function booted()
    {
        static::created(function ($transaction) {
            $transaction->account->updateBalance();
            $transaction->updateBudgetSpending();
        });

        static::updated(function ($transaction) {
            $transaction->account->updateBalance();
            $transaction->updateBudgetSpending();
        });

        static::deleted(function ($transaction) {
            $transaction->account->updateBalance();
            $transaction->updateBudgetSpending();
        });
    }

    public function updateBudgetSpending()
    {
        $budgetCategories = BudgetCategory::whereHas('budget', function ($query) {
            $query->where('start_date', '<=', $this->date)
                  ->where(function ($q) {
                      $q->where('end_date', '>=', $this->date)
                        ->orWhereNull('end_date');
                  });
        })
        ->where('category_id', $this->category_id)
        ->get();

        foreach ($budgetCategories as $budgetCategory) {
            $spent = ExpenseTransaction::where('category_id', $this->category_id)
                ->whereBetween('date', [
                    $budgetCategory->budget->start_date,
                    $budgetCategory->budget->end_date ?? now()
                ])
                ->sum('amount');

            $budgetCategory->spent = $spent;
            $budgetCategory->save();
        }
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', Carbon::now()->month)
                    ->whereYear('date', Carbon::now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('date', Carbon::now()->year);
    }

    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    public function getFormattedDateAttribute()
    {
        return $this->date->format('M d, Y');
    }

    public function getBudgetStatusAttribute()
    {
        $budget = Budget::where('category_id', $this->category_id)
            ->where('start_date', '<=', $this->date)
            ->where(function ($query) {
                $query->where('end_date', '>=', $this->date)
                      ->orWhereNull('end_date');
            })
            ->first();

        if (!$budget) {
            return null;
        }

        $spent = $budget->categories()->sum('spent');
        $total = $budget->categories()->sum('amount');

        return [
            'spent' => $spent,
            'total' => $total,
            'remaining' => $total - $spent,
            'percentage' => $total > 0 ? ($spent / $total) * 100 : 0
        ];
    }
}
