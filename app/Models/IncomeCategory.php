<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeCategory extends Model
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
        return $this->hasMany(IncomeTransaction::class, 'category_id');
    }

    public function upcomingTransactions()
    {
        return $this->hasMany(UpcomingIncome::class, 'category_id');
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
}
