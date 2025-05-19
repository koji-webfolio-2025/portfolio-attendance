<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        $start = Carbon::now()->subDays(rand(1, 10))->setTime(rand(8, 10), 0);
        $end = (clone $start)->addHours(rand(6, 9));
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'date' => $start->toDateString(),
            'clock_in' => $start,
            'clock_out' => $end,
            'note' => $this->faker->optional(0.3)->randomElement([
                '午前中は在宅勤務でした。',
                '外出のため少し遅れて出社しました。',
                '会議のため直行しました。',
                '打刻漏れのため、後から修正しています。',
                'テスト用データです。',
            ]),
        ];
    }
}
