@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-container">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Balance</h3>
            <p class="text-3xl font-bold text-primary">$24,500.00</p>
            <p class="text-sm text-gray-500 mt-1">Across all accounts</p>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Monthly Income</h3>
            <p class="text-3xl font-bold text-green-600">$8,250.00</p>
            <p class="text-sm text-gray-500 mt-1">Current month</p>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Monthly Expenses</h3>
            <p class="text-3xl font-bold text-red-600">$5,120.00</p>
            <p class="text-sm text-gray-500 mt-1">Current month</p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Income vs Expenses</h3>
            <canvas id="incomeExpenseChart" height="200"></canvas>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Expense Categories</h3>
            <canvas id="expenseCategoryChart" height="200"></canvas>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-700">Recent Transactions</h3>
            <a href="{{ url('/duit/transaction') }}" class="btn btn-primary">View All</a>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2023-05-15</td>
                        <td>Grocery Shopping</td>
                        <td>Food & Supplies</td>
                        <td class="text-red-600">-$120.50</td>
                        <td><span class="badge badge-success">Completed</span></td>
                    </tr>
                    <tr>
                        <td>2023-05-14</td>
                        <td>Salary Deposit</td>
                        <td>Income</td>
                        <td class="text-green-600">+$3,500.00</td>
                        <td><span class="badge badge-success">Completed</span></td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Income vs Expenses Chart
    const incomeExpenseCtx = document.getElementById('incomeExpenseChart').getContext('2d');
    new Chart(incomeExpenseCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Income',
                data: [6500, 7200, 7800, 8100, 8250, 8500],
                borderColor: '#10B981',
                tension: 0.4
            }, {
                label: 'Expenses',
                data: [4200, 4800, 5100, 4900, 5120, 5300],
                borderColor: '#EF4444',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

    // Expense Categories Chart
    const expenseCategoryCtx = document.getElementById('expenseCategoryChart').getContext('2d');
    new Chart(expenseCategoryCtx, {
        type: 'doughnut',
        data: {
            labels: ['Housing', 'Food', 'Transport', 'Utilities', 'Entertainment'],
            datasets: [{
                data: [1800, 950, 650, 420, 300],
                backgroundColor: [
                    '#2563EB',
                    '#10B981',
                    '#F59E0B',
                    '#6366F1',
                    '#EC4899'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
</script>
@endpush
