<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

// Album foto acara dari Google Drive: /{slug}/album
class AlbumTest extends TestCase
{
    use RefreshDatabase;

    private const FOLDER_URL = 'https://drive.google.com/drive/folders/1AbCdEfGhIjKlMnOpQrStUvWxYz?usp=sharing';
    private const FOLDER_ID  = '1AbCdEfGhIjKlMnOpQrStUvWxYz';

    protected function setUp(): void
    {
        parent::setUp();

        // Jangan sampai tes memanggil Google sungguhan
        Http::preventStrayRequests();
    }

    public function test_album_shows_photos_listed_from_google_drive(): void
    {
        config(['services.google.drive_api_key' => 'kunci-tes']);
        Http::fake(['www.googleapis.com/*' => Http::response(['files' => [
            ['id' => 'foto-satu', 'name' => 'akad.jpg'],
            ['id' => 'foto-dua',  'name' => 'resepsi.jpg'],
        ]])]);

        $invitation = $this->invitationFor(null, ['drive_folder_url' => self::FOLDER_URL]);

        $this->get("/{$invitation->slug}/album")
            ->assertOk()
            ->assertSee('2 foto')
            ->assertSee('thumbnail?id=foto-satu', false)
            ->assertSee('akad.jpg')
            ->assertDontSee('embeddedfolderview');

        Http::assertSent(fn ($request) => str_contains($request['q'], self::FOLDER_ID) && $request['key'] === 'kunci-tes');

        // Kunjungan berikutnya memakai cache, tidak memanggil Google lagi
        $this->get("/{$invitation->slug}/album")->assertOk();
        Http::assertSentCount(1);
    }

    public function test_album_falls_back_to_drive_embed_without_api_key_or_when_google_fails(): void
    {
        $invitation = $this->invitationFor(null, ['drive_folder_url' => self::FOLDER_URL]);

        config(['services.google.drive_api_key' => null]);
        $this->get("/{$invitation->slug}/album")
            ->assertOk()
            ->assertSee('embeddedfolderview?id=' . self::FOLDER_ID, false);
        Http::assertNothingSent();

        config(['services.google.drive_api_key' => 'kunci-tes']);
        Http::fake(['www.googleapis.com/*' => Http::response(['error' => 'forbidden'], 403)]);
        $this->get("/{$invitation->slug}/album")
            ->assertOk()
            ->assertSee('embeddedfolderview?id=' . self::FOLDER_ID, false);
    }

    public function test_album_is_404_without_folder_or_for_inactive_invitation(): void
    {
        $noAlbum  = $this->invitationFor();
        $inactive = $this->invitationFor(null, ['drive_folder_url' => self::FOLDER_URL, 'is_active' => false]);

        $this->get("/{$noAlbum->slug}/album")->assertNotFound();
        $this->get("/{$inactive->slug}/album")->assertNotFound();
    }

    public function test_album_link_appears_on_invitation_only_when_folder_is_set(): void
    {
        $without = $this->invitationFor();
        $this->get('/' . $without->slug)->assertOk()->assertDontSee('Buka Album');

        foreach (['netflix', 'minimalist'] as $theme) {
            $with = $this->invitationFor(null, ['theme' => $theme, 'drive_folder_url' => self::FOLDER_URL]);
            $this->get('/' . $with->slug)->assertOk()->assertSee("/{$with->slug}/album");
        }
    }

    public function test_owner_can_save_and_clear_drive_folder_link(): void
    {
        $user = User::factory()->create();
        $invitation = $this->invitationFor($user);
        $url = "/dashboard/invitations/{$invitation->slug}";
        $base = ['slug' => $invitation->slug, 'is_active' => '1'];

        $this->actingAs($user);

        foreach (['https://contoh.com/folders/1AbCdEfGhIjKlMnOp', 'bukan link', 'https://drive.google.com/file/d/abc/view'] as $bad) {
            $this->put($url, $this->invitationData([...$base, 'drive_folder_url' => $bad]))
                ->assertSessionHasErrors('drive_folder_url');
        }

        $this->put($url, $this->invitationData([...$base, 'drive_folder_url' => self::FOLDER_URL]))
            ->assertSessionHasNoErrors();
        $this->assertSame(self::FOLDER_ID, $invitation->fresh()->drive_folder_id);

        $this->get($url)->assertOk()->assertSee("/{$invitation->slug}/album");

        // Dikosongkan → album dimatikan
        $this->put($url, $this->invitationData([...$base, 'drive_folder_url' => '']))->assertSessionHasNoErrors();
        $this->assertNull($invitation->fresh()->drive_folder_id);
    }
}
