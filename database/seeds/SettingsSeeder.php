<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        DB::table('settings')->insert([
            'settingsid' => 1,
            'company' => 'YyBvIGQgZSBsIGkgcyB0IC4gYyBj',
            'city' => 'Batam',
            'address' => 'Golden Land Btams',
            'website' => 'promoney',
            'phone' => '0898999893434',
            'logo' => 'mplogo.png',
            'currency' => '$',
            'languages' => 'en',
            'dateformat' => 'd/m/Y',
        ]);
    }
}
