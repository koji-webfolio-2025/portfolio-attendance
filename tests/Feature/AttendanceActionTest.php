<?php

namespace Tests\Feature\Attendance;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceActionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::parse('2025-04-13 09:00:00'));
    }

    /** @test */
    public function can_start_attendance_once_per_day()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/attendance/start');

        $response->assertRedirect('/attendance');
        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'clock_in' => now()->format('Y-m-d') . ' 09:00:00',
        ]);
    }

    /** @test */
    public function cannot_start_attendance_twice_in_one_day()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // 1回目の出勤
        $this->post('/attendance/start');

        // 2回目の出勤
        $response = $this->post('/attendance/start');

        // 2件目が登録されていないことを確認（1件だけ）
        $this->assertEquals(1, $user->attendances()->count());
    }

    /** @test */
    public function clock_in_time_is_saved_correctly()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post('/attendance/start');

        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'clock_in' => now()->format('Y-m-d') . ' 09:00:00',
        ]);
    }
}
