<template>
  <div class="min-h-screen bg-white">
    <!-- Sidebar -->
    <aside class="fixed left-0 top-0 w-64 h-full bg-white shadow-lg p-6">
      <nav class="space-y-6">
        <div class="flex items-center space-x-3 text-gray-700">
          <DocumentIcon class="w-5 h-5" />
          <span class="font-medium">Dashboard</span>
        </div>
        <div class="flex items-center space-x-3 text-gray-700">
          <ChartBarIcon class="w-5 h-5" />
          <span>Reports</span>
        </div>
        <div class="flex items-center space-x-3 text-gray-700">
          <ChartPieIcon class="w-5 h-5" />
          <span>Analysis</span>
        </div>
        <div class="flex items-center space-x-3 text-gray-700">
          <CogIcon class="w-5 h-5" />
          <span>Settings</span>
        </div>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 p-8">
      <!-- Welcome Section -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold mb-4">Welcome to Test</h1>
        <p class="text-gray-600">
          Innovative solutions for managing your business efficiently.
        </p>
      </div>

      <!-- Statistics and Insights Grid -->
      <div class="grid grid-cols-2 gap-6 mb-8">
        <!-- Statistics Card -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold mb-4">Statistics</h2>
          <div class="space-y-4">
            <div class="flex justify-between items-center">
              <span class="text-gray-600">Autoremal</span>
              <span class="font-semibold">$12,460</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-gray-600">Spending patterns</span>
              <span class="text-green-500 font-semibold">5.9%</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-gray-600">Spending</span>
              <span class="font-semibold">16%</span>
            </div>
            <div class="mt-4">
              <div class="h-24 w-full">
                <!-- Chart will be added here -->
              </div>
            </div>
          </div>
        </div>

        <!-- Financial Insights Card -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold mb-4">Financial Insights</h2>
          <div class="space-y-6">
            <div class="flex items-center space-x-4">
              <div class="bg-green-100 p-2 rounded-full">
                <LockClosedIcon class="w-6 h-6 text-green-500" />
              </div>
              <div>
                <h3 class="font-medium">Loss Prevention Options</h3>
                <p class="text-sm text-gray-500">Protect your investments</p>
              </div>
            </div>
            <div class="flex items-center space-x-4">
              <div class="bg-green-100 p-2 rounded-full">
                <CurrencyDollarIcon class="w-6 h-6 text-green-500" />
              </div>
              <div>
                <h3 class="font-medium">Investment Opportunities</h3>
                <p class="text-sm text-gray-500">Grow your wealth</p>
              </div>
            </div>
            <div class="flex items-center space-x-4">
              <div class="bg-green-100 p-2 rounded-full">
                <ChartBarIcon class="w-6 h-6 text-green-500" />
              </div>
              <div>
                <h3 class="font-medium">Data Consolidation Suggestions</h3>
                <p class="text-sm text-gray-500">Optimize your data</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Transactions -->
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Recent Transactions</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="transaction in transactions" :key="transaction.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ transaction.date }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ transaction.description }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ transaction.category }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm" :class="transaction.amount < 0 ? 'text-red-600' : 'text-gray-900'">
                  {{ formatAmount(transaction.amount) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getStatusClass(transaction.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                    {{ transaction.status }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import { ref } from 'vue'
import {
  DocumentIcon,
  ChartBarIcon,
  ChartPieIcon,
  CogIcon,
  LockClosedIcon,
  CurrencyDollarIcon
} from '@heroicons/vue/outline'

export default {
  components: {
    DocumentIcon,
    ChartBarIcon,
    ChartPieIcon,
    CogIcon,
    LockClosedIcon,
    CurrencyDollarIcon
  },
  setup() {
    const transactions = ref([
      {
        id: 1,
        date: 'Apr 29, 2024',
        description: 'Payment',
        category: 'Office Supplies',
        amount: 560.00,
        status: 'Completed'
      },
      {
        id: 2,
        date: 'Apr 19, 2024',
        description: 'Natural',
        category: 'Services',
        amount: -50.00,
        status: 'Pending'
      },
      {
        id: 3,
        date: 'Apr 16, 2024',
        description: 'Tradian',
        category: 'Advantaglo',
        amount: 500.00,
        status: 'Completed'
      },
      {
        id: 4,
        date: 'Apr 17, 2024',
        description: 'Payment',
        category: 'Triend',
        amount: 700.00,
        status: 'Completed'
      }
    ])

    const formatAmount = (amount) => {
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
      }).format(amount)
    }

    const getStatusClass = (status) => {
      return {
        'Completed': 'bg-green-100 text-green-800',
        'Pending': 'bg-yellow-100 text-yellow-800',
        'Failed': 'bg-red-100 text-red-800'
      }[status] || 'bg-gray-100 text-gray-800'
    }

    return {
      transactions,
      formatAmount,
      getStatusClass
    }
  }
}
</script>
