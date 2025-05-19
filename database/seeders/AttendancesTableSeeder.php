<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendancesTableSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'user@example.com')->first();

        // 本人の勤怠を1件
        Attendance::create([
            'user_id' => $user->id,
            'date' => Carbon::today(),
            'clock_in' => Carbon::now()->subHours(9),
            'clock_out' => Carbon::now(),
            'note' => 'テスト用出勤',
        ]);

        // その他のダミー勤怠
        Attendance::factory()->count(5)->create();
    }
}
