<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            ['id' => 1, 'name' => 'SQL', 'created_at' => now(), 'updated_at' => now(), 'subname' => 'DF'],
            ['id' => 2, 'name' => 'External', 'created_at' => now(), 'updated_at' => now(), 'subname' => 'EX'],
            ['id' => 3, 'name' => 'Store Procedure', 'created_at' => now(), 'updated_at' => now(), 'subname' => 'SP'],
        ]);

        DB::table('dbi_types')->insert([
            ['id' => 1, 'name' => 'One Time', 'created_at' => now(), 'updated_at' => now(), 'subname' => 'OT'],
            ['id' => 2, 'name' => 'Recurring', 'created_at' => now(), 'updated_at' => now(), 'subname' => 'RE'],
            ['id' => 3, 'name' => 'Template', 'created_at' => now(), 'updated_at' => now(), 'subname' => 'TP'],
        ]);

        DB::table('priorities')->insert([
            ['id' => 1, 'name' => 'Normal', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Emergency', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Critical', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'DAT', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'SDE', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Requester', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
