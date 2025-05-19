<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\AttendanceRequest;
use App\Models\BreakTime;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('is_admin', false)->get();

        foreach ($users as $user) {
            for ($i = 1; $i <= 10; $i++) {
                $date = Carbon::today()->subDays($i);

                $clockIn = $date->copy()->setTime(rand(8, 9), rand(0, 59));
                $clockOut = (clone $clockIn)->addHours(8)->addMinutes(rand(0, 30));

                // 勤怠データ作成
                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'date' => $date->toDateString(),
                    'clock_in' => $clockIn,
                    'clock_out' => $clockOut,
                    'note' => '通常勤務',
                ]);

                // 通常の休憩データ作成
                $breakCount = rand(0, 2);
                $lastBreakEnd = clone $clockIn;

                for ($j = 0; $j < $breakCount; $j++) {
                    $breakStart = (clone $lastBreakEnd)->addMinutes(rand(60, 120));
                    $breakEnd = (clone $breakStart)->addMinutes(rand(15, 45));

                    if ($breakEnd > $clockOut) {
                        break;
                    }

                    BreakTime::create([
                        'attendance_id' => $attendance->id,
                        'break_start' => $breakStart,
                        'break_end' => $breakEnd,
                    ]);

                    $lastBreakEnd = $breakEnd;
                }

                // 50%の確率で修正申請も作成
                if (rand(0, 1)) {
                    $request = AttendanceRequest::create([
                        'attendance_id' => $attendance->id,
                        'requested_clock_in' => $clockIn->copy()->addMinutes(rand(-15, 15))->format('H:i'),
                        'requested_clock_out' => $clockOut->copy()->addMinutes(rand(-15, 15))->format('H:i'),
                        'requested_note' => fake('ja_JP')->randomElement([
                            '電車遅延のため',
                            '体調不良による遅刻',
                            'システムトラブルで打刻できず',
                        ]),
                        'is_approved' => false,
                    ]);

                    // 修正申請用の休憩も追加（最大2回）
                    $breakRequestCount = rand(1, 2);
                    $lastBreakEnd = clone $clockIn;

                    for ($k = 0; $k < $breakRequestCount; $k++) {
                        $breakStart = (clone $lastBreakEnd)->addMinutes(rand(60, 120));
                        $breakEnd = (clone $breakStart)->addMinutes(rand(15, 45));

                        if ($breakEnd > $clockOut) {
                            break;
                        }

                        $request->breakTimeRequests()->create([
                            'break_start' => $breakStart,
                            'break_end' => $breakEnd,
                        ]);

                        $lastBreakEnd = $breakEnd;
                    }
                }
            }
        }
    }
}
