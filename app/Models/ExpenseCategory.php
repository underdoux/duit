<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'color',
        'description',
        'user_id',
    ];

    public function transactions()
    {
        return $this->hasMany(ExpenseTransaction::class, 'category_id');
    }

    public function upcomingTransactions()
    {
        return $this->hasMany(UpcomingExpense::class, 'category_id');
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class, 'category_id');
    }

    public function budgetCategories()
    {
        return $this->hasMany(BudgetCategory::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalAmount($startDate = null, $endDate = null)
    {
        $query = $this->transactions();
        
        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }
        
        return $query->sum('amount');
    }

    public function getBudgetProgress($startDate = null, $endDate = null)
    {
        $spent = $this->getTotalAmount($startDate, $endDate);
        $budget = $this->budgetCategories()
            ->whereHas('budget', function ($query) use ($startDate, $endDate) {
                if ($startDate) {
                    $query->where('start_date', '>=', $startDate);
                }
                if ($endDate) {
                    $query->where('end_date', '<=', $endDate);
                }
            })
            ->sum('amount');

        return [
            'spent' => $spent,
            'budget' => $budget,
            'remaining' => $budget - $spent,
            'percentage' => $budget > 0 ? ($spent / $budget) * 100 : 0
        ];
    }
}
