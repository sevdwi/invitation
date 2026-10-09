<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// Halaman undangan publik: /{slug}
class InvitationTest extends TestCase
{
    use RefreshDatabase;

    private const OTHER_COUPLE = [
        'groom_name' => 'Budi', 'groom_full_name' => 'Budi Santoso',
        'bride_name' => 'Ani',  'bride_full_name' => 'Ani Lestari',
    ];

    public function test_invitation_page_shows_its_own_couple_and_guest_name(): void
    {
        $a = $this->invitationFor();
        $b = $this->invitationFor(null, self::OTHER_COUPLE);

        $this->get('/' . $b->slug . '?to=Pak+Joko')
            ->assertOk()
            ->assertSee('Budi')
            ->assertSee('Pak Joko')
            ->assertDontSee('Desi');

        $this->get('/' . $a->slug)->assertOk()->assertSee('Tamu Undangan');
    }

    public function test_unknown_or_inactive_invitation_returns_404(): void
    {
        $inactive = $this->invitationFor(null, ['is_active' => false]);

        $this->get('/tidak-ada')->assertNotFound();
        $this->get('/' . $inactive->slug)->assertNotFound();
        $this->postJson('/' . $inactive->slug . '/rsvp', [])->assertNotFound();
    }

    public function test_rsvp_and_wishes_are_scoped_to_their_invitation(): void
    {
        $a = $this->invitationFor();
        $b = $this->invitationFor(null, self::OTHER_COUPLE);

        $this->postJson("/{$b->slug}/rsvp", [
            'guest_name' => 'Joko', 'attendance' => 'hadir', 'guest_count' => 2,
        ])->assertOk()->assertJson(['success' => true]);

        $this->postJson("/{$b->slug}/wishes", [
            'sender_name' => 'Joko', 'message' => 'Selamat ya',
        ])->assertOk();

        $this->assertSame(0, $a->rsvps()->count());
        $this->assertSame(1, $b->rsvps()->count());

        $this->getJson("/{$a->slug}/wishes")->assertOk()->assertJsonCount(0, 'data');
        $this->getJson("/{$b->slug}/wishes")->assertOk()->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.sender_name', 'Joko');
    }

    public function test_hidden_wishes_are_not_shown_publicly(): void
    {
        $invitation = $this->invitationFor();
        $invitation->wishes()->create(['sender_name' => 'Joko', 'message' => 'Pesan tersembunyi', 'is_approved' => false]);

        $this->get('/' . $invitation->slug)->assertOk()->assertDontSee('Pesan tersembunyi');
        $this->getJson("/{$invitation->slug}/wishes")->assertJsonCount(0, 'data');
    }

    public function test_invitation_loads_correct_theme_view(): void
    {
        $netflix = $this->invitationFor(null, ['theme' => 'netflix']);
        $minimalist = $this->invitationFor(null, ['theme' => 'minimalist']);

        // Netflix theme menampilkan elemen khas Netflix (badge, nred color styling hints)
        $this->get('/' . $netflix->slug)
            ->assertOk()
            ->assertSee('The Wedding of', false);  // Netflix badge text

        // Minimalist theme menampilkan elemen minimalist (nama sans-serif, grid 2 kolom)
        $this->get('/' . $minimalist->slug)
            ->assertOk()
            ->assertSee('Mempelai', false);  // Minimalist section header

        // Theme fallback: jika theme tidak ada, pakai netflix
        $invalid = $this->invitationFor(null, ['theme' => 'nonexistent']);
        $this->get('/' . $invalid->slug)->assertOk()->assertSee('The Wedding of', false);
    }

    public function test_countdown_target_uses_wib(): void
    {
        $invitation = $this->invitationFor();

        // 11:00 WIB = 04:00 UTC
        $this->get('/' . $invitation->slug)->assertSee('2027-09-20T04:00:00.000000Z');
    }
}
