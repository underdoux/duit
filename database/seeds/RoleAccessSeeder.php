<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleAccessSeeder extends Seeder
{
    public function run()
    {
        DB::table('roleaccess')->insert([
            ['roleaccessid' => 18, 'roleid' => 2, 'userid' => 1],
            ['roleaccessid' => 19, 'roleid' => 3, 'userid' => 1],
            ['roleaccessid' => 20, 'roleid' => 4, 'userid' => 1],
            ['roleaccessid' => 21, 'roleid' => 5, 'userid' => 1],
            ['roleaccessid' => 22, 'roleid' => 6, 'userid' => 1],
            ['roleaccessid' => 23, 'roleid' => 7, 'userid' => 1],
            ['roleaccessid' => 24, 'roleid' => 8, 'userid' => 1],
            ['roleaccessid' => 25, 'roleid' => 9, 'userid' => 1],
            ['roleaccessid' => 26, 'roleid' => 10, 'userid' => 1],
            ['roleaccessid' => 27, 'roleid' => 11, 'userid' => 1],
            ['roleaccessid' => 28, 'roleid' => 12, 'userid' => 1],
            ['roleaccessid' => 29, 'roleid' => 13, 'userid' => 1],
            ['roleaccessid' => 30, 'roleid' => 14, 'userid' => 1],
            ['roleaccessid' => 31, 'roleid' => 15, 'userid' => 1],
            ['roleaccessid' => 32, 'roleid' => 16, 'userid' => 1],
            ['roleaccessid' => 33, 'roleid' => 17, 'userid' => 1],
            ['roleaccessid' => 34, 'roleid' => 18, 'userid' => 1],
            ['roleaccessid' => 35, 'roleid' => 19, 'userid' => 1],
            ['roleaccessid' => 36, 'roleid' => 20, 'userid' => 1],
        ]);
    }
}
