<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Accounts
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    // Categories
    public function incomeCategories()
    {
        return $this->hasMany(IncomeCategory::class);
    }

    public function expenseCategories()
    {
        return $this->hasMany(ExpenseCategory::class);
    }

    // Transactions
    public function incomeTransactions()
    {
        return $this->hasMany(IncomeTransaction::class);
    }

    public function expenseTransactions()
    {
        return $this->hasMany(ExpenseTransaction::class);
    }

    // Budgets and Goals
    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    public function financialGoals()
    {
        return $this->hasMany(FinancialGoal::class);
    }

    // Financial Summary Methods
    public function getTotalBalance()
    {
        return $this->accounts->sum('balance');
    }

    public function getMonthlyIncome()
    {
        return $this->incomeTransactions()
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');
    }

    public function getMonthlyExpense()
    {
        return $this->expenseTransactions()
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');
    }

    public function getActiveBudgets()
    {
        return $this->budgets()->active()->get();
    }

    public function getActiveGoals()
    {
        return $this->financialGoals()->active()->get();
    }

    // Role-based Authorization
    public function isrole($role)
    {
        // Implement your role checking logic here
        // For example, checking against a roles relationship or a role field
        return true; // Temporary return for development
    }

    public function hasPermission($permission)
    {
        // Implement your permission checking logic here
        return true; // Temporary return for development
    }

    // Account Management
    public function createAccount($data)
    {
        return $this->accounts()->create($data);
    }

    public function createBudget($data)
    {
        return $this->budgets()->create($data);
    }

    public function createGoal($data)
    {
        return $this->financialGoals()->create($data);
    }

    // Transaction Methods
    public function recordIncome($data)
    {
        return $this->incomeTransactions()->create($data);
    }

    public function recordExpense($data)
    {
        return $this->expenseTransactions()->create($data);
    }

    // Category Management
    public function createIncomeCategory($data)
    {
        return $this->incomeCategories()->create($data);
    }

    public function createExpenseCategory($data)
    {
        return $this->expenseCategories()->create($data);
    }

    // Financial Reports
    public function getIncomeReport($startDate, $endDate)
    {
        return $this->incomeTransactions()
            ->whereBetween('date', [$startDate, $endDate])
            ->with('category')
            ->get()
            ->groupBy('category.name');
    }

    public function getExpenseReport($startDate, $endDate)
    {
        return $this->expenseTransactions()
            ->whereBetween('date', [$startDate, $endDate])
            ->with('category')
            ->get()
            ->groupBy('category.name');
    }

    public function getBudgetOverview()
    {
        return $this->budgets()
            ->active()
            ->with('categories.category')
            ->get()
            ->map(function ($budget) {
                return [
                    'name' => $budget->name,
                    'progress' => $budget->getProgress(),
                    'categories' => $budget->categories->map(function ($category) {
                        return [
                            'name' => $category->category->name,
                            'progress' => $category->getProgress()
                        ];
                    })
                ];
            });
    }

    public function getGoalsOverview()
    {
        return $this->financialGoals()
            ->active()
            ->get()
            ->map(function ($goal) {
                return [
                    'name' => $goal->name,
                    'progress' => $goal->getProgress(),
                    'remaining_days' => $goal->remaining_days,
                    'required_daily_amount' => $goal->required_daily_amount
                ];
            });
    }
}
