<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('user_classes')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('user_classes')->insert([
            ['name' => 'A1', 'created_at' => \now(), 'updated_at' => \now()],
            ['name' => 'A2', 'created_at' => \now(), 'updated_at' => \now()],
            ['name' => 'A3', 'created_at' => \now(), 'updated_at' => \now()],
        ]);
    }
}
