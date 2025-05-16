<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\IncomeTransaction;
use App\Models\ExpenseTransaction;
use App\Models\Budget;
use App\Models\FinancialGoal;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        $data = [
            'totalBalance' => $user->getTotalBalance(),
            'monthlyIncome' => $user->getMonthlyIncome(),
            'monthlyExpense' => $user->getMonthlyExpense(),
            'incomeVsExpense' => $this->getIncomeVsExpenseData(),
            'expensesByCategory' => $this->getExpensesByCategoryData(),
            'recentTransactions' => $this->getRecentTransactions(),
            'activeBudgets' => $this->getActiveBudgets(),
            'activeGoals' => $this->getActiveGoals(),
            'accountBalances' => $this->getAccountBalances(),
        ];

        return view('home', $data);
    }

    private function getIncomeVsExpenseData()
    {
        $user = auth()->user();
        $data = collect();

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $data->push([
                'month' => $date->format('M'),
                'income' => $user->incomeTransactions()
                    ->whereYear('date', $date->year)
                    ->whereMonth('date', $date->month)
                    ->sum('amount'),
                'expense' => $user->expenseTransactions()
                    ->whereYear('date', $date->year)
                    ->whereMonth('date', $date->month)
                    ->sum('amount')
            ]);
        }

        return $data;
    }

    private function getExpensesByCategoryData()
    {
        $user = auth()->user();
        $startOfMonth = Carbon::now()->startOfMonth();

        return $user->expenseCategories()
            ->withSum(['transactions' => function ($query) use ($startOfMonth) {
                $query->where('date', '>=', $startOfMonth);
            }], 'amount')
            ->orderByDesc('transactions_sum_amount')
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'amount' => $category->transactions_sum_amount ?? 0,
                    'color' => $category->color
                ];
            });
    }

    private function getRecentTransactions()
    {
        $user = auth()->user();
        
        $income = $user->incomeTransactions()
            ->with(['category', 'account'])
            ->latest('date')
            ->limit(5)
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'date' => $transaction->formatted_date,
                    'description' => $transaction->description,
                    'category' => $transaction->category->name,
                    'amount' => '+' . $transaction->formatted_amount,
                    'type' => 'income',
                    'account' => $transaction->account->name
                ];
            });

        $expenses = $user->expenseTransactions()
            ->with(['category', 'account'])
            ->latest('date')
            ->limit(5)
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'date' => $transaction->formatted_date,
                    'description' => $transaction->description,
                    'category' => $transaction->category->name,
                    'amount' => '-' . $transaction->formatted_amount,
                    'type' => 'expense',
                    'account' => $transaction->account->name
                ];
            });

        return $income->concat($expenses)
            ->sortByDesc('date')
            ->take(5)
            ->values();
    }

    private function getActiveBudgets()
    {
        return auth()->user()->getActiveBudgets()
            ->map(function ($budget) {
                $progress = $budget->getProgress();
                return [
                    'id' => $budget->id,
                    'name' => $budget->name,
                    'amount' => $budget->amount,
                    'spent' => $progress['spent'],
                    'remaining' => $progress['remaining'],
                    'percentage' => $progress['percentage'],
                    'status' => $progress['status']
                ];
            });
    }

    private function getActiveGoals()
    {
        return auth()->user()->getActiveGoals()
            ->map(function ($goal) {
                $progress = $goal->getProgress();
                return [
                    'id' => $goal->id,
                    'name' => $goal->name,
                    'target_amount' => $goal->target_amount,
                    'current_amount' => $goal->current_amount,
                    'remaining' => $progress['remaining'],
                    'percentage' => $progress['percentage'],
                    'status' => $progress['status'],
                    'target_date' => $goal->target_date ? $goal->target_date->format('M d, Y') : null,
                    'remaining_days' => $goal->remaining_days,
                    'required_daily_amount' => $goal->required_daily_amount
                ];
            });
    }

    private function getAccountBalances()
    {
        return auth()->user()->accounts()
            ->get()
            ->map(function ($account) {
                return [
                    'id' => $account->id,
                    'name' => $account->name,
                    'balance' => $account->balance,
                    'type' => $account->type,
                    'currency' => $account->currency
                ];
            });
    }
}
