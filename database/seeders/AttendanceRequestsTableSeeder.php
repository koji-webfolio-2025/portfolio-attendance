<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\AttendanceRequest;
use Illuminate\Database\Seeder;

class AttendanceRequestsTableSeeder extends Seeder
{
    public function run(): void
    {
        // 勤怠から3件ピックアップして申請を作成
        Attendance::inRandomOrder()->take(10)->get()->each(function ($attendance) {
            AttendanceRequest::factory()->create([
                'attendance_id' => $attendance->id,
            ]);
        });
    }
}
