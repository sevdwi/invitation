<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// Daftar hadir: /{slug}/hadir (tamu) dan QR + rekap di dashboard (pemilik)
class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_attendance_form_is_displayed_with_guest_name_from_link(): void
    {
        $invitation = $this->invitationFor();

        $this->get("/{$invitation->slug}/hadir?to=Pak+Joko")
            ->assertOk()
            ->assertSee('Daftar Hadir')
            ->assertSee('Desi')
            ->assertSee('value="Pak Joko"', false);
    }

    public function test_guest_can_fill_attendance_and_it_is_scoped_to_the_invitation(): void
    {
        $a = $this->invitationFor();
        $b = $this->invitationFor(null, ['groom_name' => 'Budi', 'bride_name' => 'Ani']);

        $this->post("/{$b->slug}/hadir", [
            'guest_name' => 'Joko', 'address' => 'Cilacap', 'guest_phone' => '08123456789', 'guest_count' => 3,
        ])->assertRedirect("/{$b->slug}/hadir");

        $this->assertSame(0, $a->attendances()->count());
        $this->assertDatabaseHas('attendances', [
            'invitation_id' => $b->id, 'guest_name' => 'Joko', 'address' => 'Cilacap', 'guest_count' => 3,
        ]);

        // Setelah mengisi, tamu melihat ucapan terima kasih, bukan form lagi
        $this->get("/{$b->slug}/hadir")
            ->assertSee('Terima kasih, Joko!')
            ->assertDontSee('name="guest_name"', false);
    }

    public function test_attendance_is_validated(): void
    {
        $invitation = $this->invitationFor();

        $this->post("/{$invitation->slug}/hadir", ['guest_name' => '', 'guest_count' => 99])
            ->assertSessionHasErrors(['guest_name', 'guest_count']);

        $this->assertDatabaseCount('attendances', 0);
    }

    public function test_attendance_is_closed_for_inactive_invitation(): void
    {
        $inactive = $this->invitationFor(null, ['is_active' => false]);

        $this->get("/{$inactive->slug}/hadir")->assertNotFound();
        $this->post("/{$inactive->slug}/hadir", ['guest_name' => 'Joko', 'guest_count' => 1])->assertNotFound();
    }

    public function test_owner_sees_qr_and_attendance_list(): void
    {
        $user = User::factory()->create();
        $invitation = $this->invitationFor($user);
        $invitation->attendances()->create(['guest_name' => 'Joko', 'guest_count' => 2]);
        $invitation->attendances()->create(['guest_name' => 'Wati', 'guest_count' => 1]);

        $this->actingAs($user)->get("/dashboard/invitations/{$invitation->slug}")
            ->assertOk()
            ->assertSee('Joko')
            ->assertSee('Wati')
            ->assertSee('<svg', false)
            ->assertSee("/{$invitation->slug}/hadir");

        $this->get("/dashboard/invitations/{$invitation->slug}/qr")
            ->assertOk()
            ->assertSee('<svg', false)
            ->assertSee('Scan untuk mengisi daftar hadir');
    }

    public function test_owner_can_delete_attendance_but_others_cannot(): void
    {
        $user = User::factory()->create();
        $mine = $this->invitationFor($user);
        $other = $this->invitationFor(User::factory()->create(), ['groom_name' => 'Budi']);

        $myRow    = $mine->attendances()->create(['guest_name' => 'Joko', 'guest_count' => 1]);
        $otherRow = $other->attendances()->create(['guest_name' => 'Wati', 'guest_count' => 1]);

        $this->actingAs($user);

        // Undangan orang lain, maupun baris milik undangan lain lewat undangan sendiri
        $this->get("/dashboard/invitations/{$other->slug}/qr")->assertNotFound();
        $this->delete("/dashboard/invitations/{$other->slug}/attendances/{$otherRow->id}")->assertNotFound();
        $this->delete("/dashboard/invitations/{$mine->slug}/attendances/{$otherRow->id}")->assertNotFound();
        $this->assertDatabaseCount('attendances', 2);

        $this->delete("/dashboard/invitations/{$mine->slug}/attendances/{$myRow->id}")->assertSessionHasNoErrors();
        $this->assertSame(0, $mine->attendances()->count());
        $this->assertSame(1, $other->attendances()->count());
    }
}
