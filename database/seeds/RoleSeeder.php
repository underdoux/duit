<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('role')->insert([
            ['roleid' => 2, 'name' => 'Transactions'],
            ['roleid' => 3, 'name' => 'Income'],
            ['roleid' => 4, 'name' => 'Expense'],
            ['roleid' => 5, 'name' => 'Accounts'],
            ['roleid' => 6, 'name' => 'Track Budget'],
            ['roleid' => 7, 'name' => 'Set Goals'],
            ['roleid' => 8, 'name' => 'Calendar'],
            ['roleid' => 9, 'name' => 'Income Category'],
            ['roleid' => 10, 'name' => 'Expense Category'],
            ['roleid' => 11, 'name' => 'Income Reports'],
            ['roleid' => 12, 'name' => 'Expense Category'],
            ['roleid' => 13, 'name' => 'Income vs Expense Reports'],
            ['roleid' => 14, 'name' => 'Income Monthly Report'],
            ['roleid' => 15, 'name' => 'Expense Monthly Report'],
            ['roleid' => 16, 'name' => 'Account Transaction Reports'],
            ['roleid' => 17, 'name' => 'User Role'],
            ['roleid' => 18, 'name' => 'Application Setting'],
            ['roleid' => 19, 'name' => 'Upcoming Income'],
            ['roleid' => 20, 'name' => 'Upcoming Expense'],
        ]);
    }
}
