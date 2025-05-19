<?php

namespace Tests\Feature\Attendance;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAttendanceDetailAndRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function attendance_detail_shows_correct_user_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $date = '2025-04-12';

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => $date,
            'clock_in' => Carbon::parse($date . ' 09:00'),
            'clock_out' => Carbon::parse($date . ' 18:00'),
            'note' => 'Regular shift',
        ]);

        $response = $this->get("/attendances/{$attendance->id}");

        $response->assertSee($user->name)
            ->assertSee(Carbon::parse($attendance->date)->format('Y年m月d日 (D)'))
            ->assertSee('09:00')
            ->assertSee('18:00')
            ->assertSee('Regular shift');
    }

    /** @test */
    public function user_cannot_submit_invalid_attendance_request()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => '2025-04-12',
        ]);

        $response = $this->post("/attendances/{$attendance->id}/request-edit", [
            'clock_in' => '18:00',
            'clock_out' => '09:00',
            'note' => '',
        ]);

        $response->assertSessionHasErrors([
            'clock_out' => '出勤時間もしくは退勤時間が不適切な値です',
            'note' => '備考を記入してください',
        ]);
    }

    /** @test */
    public function user_can_submit_valid_attendance_request()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => '2025-04-12',
        ]);

        $response = $this->post("/attendances/{$attendance->id}/request-edit", [
            'clock_in' => '09:00',
            'clock_out' => '18:00',
            'note' => '修正申請テスト',
        ]);

        $response->assertRedirect(route('attendance.show', $attendance->id));
        $this->assertDatabaseHas('attendance_requests', [
            'attendance_id' => $attendance->id,
            'requested_note' => '修正申請テスト',
        ]);
    }
}
