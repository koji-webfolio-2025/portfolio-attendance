<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\BreakTime;
use Illuminate\Database\Seeder;

class BreakTimesTableSeeder extends Seeder
{
    public function run(): void
    {
        // 全ての勤怠に対してランダムで休憩レコードを追加
        Attendance::all()->each(function ($attendance) {
            BreakTime::factory()->count(rand(1, 2))->create([
                'attendance_id' => $attendance->id,
            ]);
        });
    }
}
