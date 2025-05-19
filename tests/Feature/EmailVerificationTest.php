<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_receives_verification_email_after_registration()
    {
        Event::fake();

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);
        $this->post('/email/verification-notification');

        Event::assertDispatched(Verified::class, 0); // 通知は送るが verified ではない
    }

    /** @test */
    public function user_can_verify_email_using_signed_url()
    {
        $user = User::factory()->unverified()->create();
        $this->actingAs($user);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify', now()->addMinutes(60), [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $response = $this->get($verificationUrl);

        $response->assertRedirect('/attendance');
        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
