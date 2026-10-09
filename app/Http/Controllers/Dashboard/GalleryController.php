<?php
// FILE: app/Http/Controllers/Dashboard/GalleryController.php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function store(Request $request, Invitation $myInvitation): RedirectResponse
    {
        $request->validate([
            'photos'   => 'required|array|max:10',
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:3072',
            'caption'  => 'nullable|string|max:255',
        ], [
            'photos.required' => 'Pilih minimal satu foto.',
            'photos.max'      => 'Maksimal 10 foto sekali unggah.',
            'photos.*.image'  => 'File harus berupa gambar.',
            'photos.*.mimes'  => 'Format foto harus JPG atau PNG.',
            'photos.*.max'    => 'Ukuran tiap foto maksimal 3 MB.',
        ]);

        $order = (int) $myInvitation->galleries()->max('order');

        foreach ($request->file('photos') as $photo) {
            $myInvitation->galleries()->create([
                'image_path' => $photo->store('gallery', 'public'),
                'caption'    => $request->input('caption'),
                'order'      => ++$order,
            ]);
        }

        return back()->with('success', 'Foto berhasil ditambahkan ke galeri.');
    }

    public function destroy(Invitation $myInvitation, int $gallery): RedirectResponse
    {
        $photo = $myInvitation->galleries()->findOrFail($gallery);

        Storage::disk('public')->delete($photo->image_path);
        $photo->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}
