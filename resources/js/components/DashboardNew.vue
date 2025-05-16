<template>
  <div class="min-h-screen bg-white">
    <div class="flex">
      <!-- Sidebar -->
      <aside class="w-64 h-screen bg-white shadow-lg p-6">
        <nav class="space-y-6">
          <a href="#" class="flex items-center space-x-3 text-gray-700">
            <span class="font-medium">Dashboard</span>
          </a>
          <a href="#" class="flex items-center space-x-3 text-gray-700">
            <span>Reports</span>
          </a>
          <a href="#" class="flex items-center space-x-3 text-gray-700">
            <span>Analysis</span>
          </a>
          <a href="#" class="flex items-center space-x-3 text-gray-700">
            <span>Settings</span>
          </a>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 p-8">
        <h1 class="text-2xl font-bold mb-6">Dashboard</h1>
        
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Statistics</h2>
            <div class="space-y-4">
              <div class="flex justify-between">
                <span>Total Revenue</span>
                <span class="font-medium">$12,460</span>
              </div>
              <div class="flex justify-between">
                <span>Growth</span>
                <span class="text-green-500">5.9%</span>
              </div>
            </div>
          </div>
          
          <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Recent Activity</h2>
            <div class="space-y-4">
              <div class="flex justify-between">
                <span>Last Transaction</span>
                <span class="font-medium">$560.00</span>
              </div>
              <div class="flex justify-between">
                <span>Status</span>
                <span class="text-green-500">Completed</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
          <div class="p-6 border-b">
            <h2 class="text-lg font-semibold">Recent Transactions</h2>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(transaction, index) in transactions" :key="index">
                  <td class="px-6 py-4 whitespace-nowrap text-sm">{{ transaction.date }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">{{ transaction.description }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">{{ transaction.amount }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <span :class="getStatusClass(transaction.status)">
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
  </div>
</template>

<script>
export default {
  data() {
    return {
      transactions: [
        { date: '2024-04-29', description: 'Office Supplies', amount: '$560.00', status: 'Completed' },
        { date: '2024-04-19', description: 'Services', amount: '-$50.00', status: 'Pending' },
        { date: '2024-04-16', description: 'Marketing', amount: '$500.00', status: 'Completed' },
        { date: '2024-04-17', description: 'Equipment', amount: '$700.00', status: 'Completed' }
      ]
    }
  },
  methods: {
    getStatusClass(status) {
      return {
        'Completed': 'text-green-600',
        'Pending': 'text-yellow-600',
        'Failed': 'text-red-600'
      }[status] || 'text-gray-600'
    }
  }
}
</script>
