<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\BreakTime;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class BreakTimeFactory extends Factory
{
    protected $model = BreakTime::class;

    public function definition(): array
    {
        static $lastEnd = null;

        $attendance = Attendance::inRandomOrder()->first();

        if (!$lastEnd || $lastEnd->diffInHours(now()) > 4) {
            $start = Carbon::parse($attendance->clock_in)->addHours(rand(1, 3));
        } else {
            $start = (clone $lastEnd)->addMinutes(rand(30, 90));
        }

        $end = (clone $start)->addMinutes(rand(15, 30));
        $lastEnd = clone $end;

        return [
            'attendance_id' => $attendance->id,
            'break_start' => $start,
            'break_end' => $end,
        ];
    }
}
