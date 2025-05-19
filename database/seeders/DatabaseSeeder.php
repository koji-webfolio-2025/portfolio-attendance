<?php

namespace Database\Seeders;

use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        app()->singleton(\Faker\Generator::class, function () {
            return FakerFactory::create('ja_JP');
        });

        $this->call([
            UsersTableSeeder::class,
            AttendancesTableSeeder::class,
            BreakTimesTableSeeder::class,
            AttendanceRequestsTableSeeder::class,
            AttendanceSeeder::class,
        ]);
    }
}
