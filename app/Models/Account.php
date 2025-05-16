<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'name',
        'balance',
        'type',
        'currency',
        'description',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function incomeTransactions()
    {
        return $this->hasMany(IncomeTransaction::class);
    }

    public function expenseTransactions()
    {
        return $this->hasMany(ExpenseTransaction::class);
    }

    public function upcomingIncome()
    {
        return $this->hasMany(UpcomingIncome::class);
    }

    public function upcomingExpenses()
    {
        return $this->hasMany(UpcomingExpense::class);
    }

    public function goals()
    {
        return $this->hasMany(FinancialGoal::class);
    }

    public function updateBalance()
    {
        $income = $this->incomeTransactions()->sum('amount');
        $expenses = $this->expenseTransactions()->sum('amount');
        $this->balance = $income - $expenses;
        $this->save();
    }
}
