<?php
// FILE: app/Http/Controllers/AlbumController.php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Services\GoogleDriveAlbum;

// Album foto acara: fotonya tersimpan di Google Drive pemilik undangan
class AlbumController extends Controller
{
    public function show(Invitation $invitation, GoogleDriveAlbum $album)
    {
        $folderId = $invitation->drive_folder_id ?? abort(404);

        return view('album.show', [
            'invitation' => $invitation,
            'folderId'   => $folderId,
            // null → daftar foto tidak bisa diambil, view memakai tampilan embed Drive
            'photos'     => $album->photos($folderId),
        ]);
    }
}
