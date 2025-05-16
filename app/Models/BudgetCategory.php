<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetCategory extends Model
{
    protected $fillable = [
        'amount',
        'spent',
        'budget_id',
        'category_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'spent' => 'decimal:2',
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    public function getProgress()
    {
        return [
            'spent' => $this->spent,
            'amount' => $this->amount,
            'remaining' => $this->amount - $this->spent,
            'percentage' => $this->amount > 0 ? ($this->spent / $this->amount) * 100 : 0,
            'status' => $this->getStatus()
        ];
    }

    public function getStatus()
    {
        $percentage = $this->amount > 0 ? ($this->spent / $this->amount) * 100 : 0;
        
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

    public function updateSpent()
    {
        $this->spent = ExpenseTransaction::where('category_id', $this->category_id)
            ->whereBetween('date', [
                $this->budget->start_date,
                $this->budget->end_date ?? now()
            ])
            ->sum('amount');
        
        $this->save();
    }
}
