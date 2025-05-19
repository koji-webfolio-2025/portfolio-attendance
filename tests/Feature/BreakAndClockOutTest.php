<?php

namespace Tests\Feature\Attendance;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BreakAndClockOutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::parse('2025-04-13 09:00:00'));
    }

    /** @test */
    public function user_can_start_and_end_break_multiple_times()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post('/attendance/start');

        $response1 = $this->post('/attendance/break-start');
        $response1->assertStatus(302);

        Carbon::setTestNow(Carbon::parse('2025-04-13 09:30:00'));
        $response2 = $this->post('/attendance/break-end');
        $response2->assertStatus(302);

        Carbon::setTestNow(Carbon::parse('2025-04-13 10:00:00'));
        $this->post('/attendance/break-start');

        $this->assertDatabaseCount('break_times', 2);
    }

    /** @test */
    public function user_can_clock_out_once_after_work()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post('/attendance/start');

        Carbon::setTestNow(Carbon::parse('2025-04-13 17:00:00'));
        $this->post('/attendance/clock-out');

        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'clock_out' => now()->format('Y-m-d') . ' 17:00:00',
        ]);
    }
}
