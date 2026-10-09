<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->user = User::factory()->create();
    }

    public function test_dashboard_lists_only_my_invitations(): void
    {
        $this->invitationFor($this->user);
        $this->invitationFor(User::factory()->create(), ['groom_name' => 'Budi', 'bride_name' => 'Ani']);

        $this->actingAs($this->user)->get('/dashboard')
            ->assertOk()
            ->assertSee('Desi')
            ->assertDontSee('Budi');
    }

    public function test_create_form_is_displayed(): void
    {
        $this->actingAs($this->user)->get('/dashboard/invitations/create')
            ->assertOk()
            ->assertSee('name="groom_full_name"', false);
    }

    public function test_user_can_create_invitation_with_uploads_and_choose_theme(): void
    {
        $response = $this->actingAs($this->user)->post('/dashboard/invitations', $this->invitationData([
            'theme'        => 'minimalist',
            'groom_photo'  => UploadedFile::fake()->image('groom.jpg'),
        ]));

        $response->assertRedirect('/dashboard/invitations/desi-zaky');

        $invitation = Invitation::sole();
        $this->assertSame($this->user->id, $invitation->user_id);
        $this->assertSame('desi-zaky', $invitation->slug);
        $this->assertSame('minimalist', $invitation->theme);
        $this->assertTrue($invitation->is_active);
        Storage::disk('public')->assertExists($invitation->groom_photo);

        // Nama yang sama → slug berbeda
        $this->post('/dashboard/invitations', $this->invitationData(['theme' => 'netflix']))
            ->assertRedirect('/dashboard/invitations/desi-zaky-2');
    }

    public function test_store_validates_required_and_theme_fields(): void
    {
        $this->actingAs($this->user)->post('/dashboard/invitations', [])
            ->assertSessionHasErrors(['theme', 'groom_name', 'reception_date']);

        $this->post('/dashboard/invitations', $this->invitationData(['theme' => 'invalid-theme']))
            ->assertSessionHasErrors('theme');
    }

    public function test_manage_page_shows_rsvp_recap(): void
    {
        $invitation = $this->invitationFor($this->user);
        $invitation->rsvps()->create(['guest_name' => 'Joko', 'attendance' => 'hadir', 'guest_count' => 3]);
        $invitation->rsvps()->create(['guest_name' => 'Wati', 'attendance' => 'tidak_hadir', 'guest_count' => 1]);

        $this->actingAs($this->user)->get("/dashboard/invitations/{$invitation->slug}")
            ->assertOk()
            ->assertSee('Joko')
            ->assertSee('Wati')
            ->assertViewHas('stats', ['hadir' => 1, 'tidak_hadir' => 1, 'belum_pasti' => 0, 'total_tamu' => 3]);

        $this->get("/dashboard/invitations/{$invitation->slug}/edit")->assertOk()->assertSee('Muhammad Desi Pratama');
    }

    public function test_user_cannot_access_someone_elses_invitation(): void
    {
        $other = $this->invitationFor(User::factory()->create());
        $orphan = $this->invitationFor(null, ['groom_name' => 'Budi']);
        $wish  = $other->wishes()->create(['sender_name' => 'Joko', 'message' => 'Selamat']);

        $this->actingAs($this->user);

        foreach ([$other, $orphan] as $invitation) {
            $base = "/dashboard/invitations/{$invitation->slug}";

            $this->get($base)->assertNotFound();
            $this->get("$base/edit")->assertNotFound();
            $this->put($base, $this->invitationData(['slug' => 'diambil-alih']))->assertNotFound();
            $this->delete($base)->assertNotFound();
        }

        $this->patch("/dashboard/invitations/{$other->slug}/wishes/{$wish->id}")->assertNotFound();

        $this->assertSame(2, Invitation::count());
        $this->assertTrue($wish->fresh()->is_approved);
    }

    public function test_user_can_update_invitation_and_change_slug(): void
    {
        $invitation = $this->invitationFor($this->user, [
            'groom_photo' => UploadedFile::fake()->image('old.jpg')->store('photos', 'public'),
        ]);
        $oldPhoto = $invitation->groom_photo;

        $this->actingAs($this->user)->put("/dashboard/invitations/{$invitation->slug}", $this->invitationData([
            'slug'        => 'pernikahan-kami',
            'groom_name'  => 'Dede',
            'is_active'   => '1',
            'groom_photo' => UploadedFile::fake()->image('new.jpg'),
        ]))->assertRedirect('/dashboard/invitations/pernikahan-kami');

        $invitation->refresh();
        $this->assertSame('pernikahan-kami', $invitation->slug);
        $this->assertSame('Dede', $invitation->groom_name);
        $this->assertNotSame($oldPhoto, $invitation->groom_photo);
        Storage::disk('public')->assertMissing($oldPhoto);
        Storage::disk('public')->assertExists($invitation->groom_photo);

        $this->get('/pernikahan-kami')->assertOk();
        $this->get('/desi-zaky')->assertNotFound();
    }

    public function test_update_without_new_file_keeps_existing_file(): void
    {
        $path = UploadedFile::fake()->image('old.jpg')->store('photos', 'public');
        $invitation = $this->invitationFor($this->user, ['groom_photo' => $path]);

        $this->actingAs($this->user)->put("/dashboard/invitations/{$invitation->slug}", $this->invitationData([
            'slug' => $invitation->slug, 'is_active' => '1',
        ]))->assertSessionHasNoErrors();

        $this->assertSame($path, $invitation->fresh()->groom_photo);
        Storage::disk('public')->assertExists($path);
    }

    public function test_update_rejects_reserved_taken_or_malformed_slug(): void
    {
        $invitation = $this->invitationFor($this->user);
        $this->invitationFor(User::factory()->create(), ['slug' => 'sudah-dipakai']);

        $this->actingAs($this->user);

        foreach (['dashboard', 'sudah-dipakai', 'Ada Spasi', ''] as $slug) {
            $this->put("/dashboard/invitations/{$invitation->slug}", $this->invitationData(['slug' => $slug]))
                ->assertSessionHasErrors('slug');
        }

        $this->assertSame('desi-zaky', $invitation->fresh()->slug);
    }

    public function test_user_can_change_theme(): void
    {
        $invitation = $this->invitationFor($this->user, ['theme' => 'netflix']);

        $this->actingAs($this->user)->put("/dashboard/invitations/{$invitation->slug}", $this->invitationData([
            'slug' => $invitation->slug, 'theme' => 'minimalist', 'is_active' => '1',
        ]))->assertSessionHasNoErrors();

        $this->assertSame('minimalist', $invitation->fresh()->theme);
    }

    public function test_deactivated_invitation_is_hidden_from_public_but_still_manageable(): void
    {
        $invitation = $this->invitationFor($this->user);

        // Checkbox tidak dicentang → is_active tidak terkirim
        $this->actingAs($this->user)->put("/dashboard/invitations/{$invitation->slug}", $this->invitationData([
            'slug' => $invitation->slug,
        ]))->assertSessionHasNoErrors();

        $this->assertFalse($invitation->fresh()->is_active);
        $this->get('/' . $invitation->slug)->assertNotFound();
        $this->get("/dashboard/invitations/{$invitation->slug}")->assertOk();
    }

    public function test_user_can_upload_and_delete_gallery_photos(): void
    {
        $invitation = $this->invitationFor($this->user);
        $base = "/dashboard/invitations/{$invitation->slug}/gallery";

        $this->actingAs($this->user)->post($base, [
            'photos'  => [UploadedFile::fake()->image('a.jpg'), UploadedFile::fake()->image('b.png')],
            'caption' => 'Prewedding',
        ])->assertSessionHasNoErrors();

        $photos = $invitation->galleries()->get();
        $this->assertCount(2, $photos);
        $this->assertSame([1, 2], $photos->pluck('order')->all());
        Storage::disk('public')->assertExists($photos[0]->image_path);

        $this->post($base, ['photos' => [UploadedFile::fake()->create('virus.pdf', 10)]])
            ->assertSessionHasErrors('photos.0');
        $this->post($base, [])->assertSessionHasErrors('photos');

        $this->delete("$base/{$photos[0]->id}")->assertSessionHasNoErrors();
        $this->assertSame(1, $invitation->galleries()->count());
        Storage::disk('public')->assertMissing($photos[0]->image_path);
    }

    public function test_user_can_hide_show_and_delete_wishes(): void
    {
        $invitation = $this->invitationFor($this->user);
        $wish = $invitation->wishes()->create(['sender_name' => 'Joko', 'message' => 'Selamat']);
        $url  = "/dashboard/invitations/{$invitation->slug}/wishes/{$wish->id}";

        $this->actingAs($this->user)->patch($url);
        $this->assertFalse($wish->fresh()->is_approved);

        $this->patch($url);
        $this->assertTrue($wish->fresh()->is_approved);

        $this->delete($url);
        $this->assertSame(0, $invitation->wishes()->count());
    }

    public function test_user_can_delete_invitation_with_its_files(): void
    {
        $photo = UploadedFile::fake()->image('g.jpg')->store('photos', 'public');
        $invitation = $this->invitationFor($this->user, ['groom_photo' => $photo]);
        $gallery = $invitation->galleries()->create([
            'image_path' => UploadedFile::fake()->image('x.jpg')->store('gallery', 'public'),
        ]);
        $invitation->rsvps()->create(['guest_name' => 'Joko', 'attendance' => 'hadir', 'guest_count' => 1]);

        $this->actingAs($this->user)->delete("/dashboard/invitations/{$invitation->slug}")
            ->assertRedirect('/dashboard');

        $this->assertSame(0, Invitation::count());
        $this->assertDatabaseCount('rsvps', 0);
        $this->assertDatabaseCount('galleries', 0);
        Storage::disk('public')->assertMissing($photo);
        Storage::disk('public')->assertMissing($gallery->image_path);
    }
}
