<?php

namespace Tests\Feature\Admin;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class AdminAttendanceManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_all_attendances_of_the_day()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        Gate::define('admin', fn(User $user) => $user->is_admin);

        $this->actingAs($admin);

        Attendance::factory()->count(3)->create([
            'user_id' => $admin->id,
        ]);

        $response = $this->get('/admin/attendances');

        $response->assertStatus(200);
        $response->assertSee('勤怠一覧');
    }

    /** @test */
    public function test_admin_can_update_attendance()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $attendance = Attendance::factory()->create();

        $this->actingAs($admin);

        $response = $this->put(route('admin.attendance.update', $attendance->id), [
            'clock_in' => '09:00',
            'clock_out' => '18:00',
            'note' => '管理者による修正',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('attendances', [
            'id' => $attendance->id,
            'note' => '管理者による修正',
        ]);
    }
}
