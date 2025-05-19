<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\AttendanceRequest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AttendanceRequestFactory extends Factory
{
    protected $model = AttendanceRequest::class;

    public function definition(): array
    {
        $clockIn = Carbon::now()->subHours(9);
        $clockOut = (clone $clockIn)->addHours(8);

        return [
            'attendance_id' => Attendance::inRandomOrder()->first()->id,
            'requested_clock_in' => $clockIn->format('H:i'),
            'requested_clock_out' => $clockOut->format('H:i'),
            'requested_note' => fake('ja_JP')->randomElement([
                '寝坊しました',
                '電車が遅れました',
                '体調不良のため遅刻しました',
                '前日からの作業延長の影響です',
            ]),
            'is_approved' => false,
        ];
    }
}
