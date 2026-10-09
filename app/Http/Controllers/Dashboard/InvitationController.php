<?php
// FILE: app/Http/Controllers/Dashboard/InvitationController.php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveInvitationRequest;
use App\Models\Invitation;
use App\Support\QrCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvitationController extends Controller
{
    // Kolom file → folder penyimpanan di disk 'public'
    private const UPLOADS = [
        'groom_photo' => 'photos',
        'bride_photo' => 'photos',
        'hero_image'  => 'heroes',
        'music_file'  => 'music',
    ];

    public function index(Request $request)
    {
        $invitations = $request->user()->invitations()
            ->withCount(['rsvps', 'wishes'])
            ->latest()
            ->get();

        return view('dashboard.index', compact('invitations'));
    }

    public function create()
    {
        return view('dashboard.invitations.create');
    }

    public function store(SaveInvitationRequest $request): RedirectResponse
    {
        // Slug dibuat otomatis oleh model dari nama mempelai
        $invitation = $request->user()->invitations()->create([
            ...$this->withUploads($request),
            'is_active' => true,
        ]);

        return redirect()
            ->route('dashboard.invitations.show', $invitation)
            ->with('success', 'Undangan berhasil dibuat!');
    }

    public function show(Invitation $myInvitation)
    {
        $invitation = $myInvitation->load(['galleries', 'wishes']);
        $rsvps      = $invitation->rsvps()->latest()->get();

        $stats = [
            'hadir'       => $rsvps->where('attendance', 'hadir')->count(),
            'tidak_hadir' => $rsvps->where('attendance', 'tidak_hadir')->count(),
            'belum_pasti' => $rsvps->where('attendance', 'belum_pasti')->count(),
            'total_tamu'  => $rsvps->where('attendance', 'hadir')->sum('guest_count'),
        ];

        $attendances  = $invitation->attendances()->get();
        $attendanceQr = QrCode::svg(route('attendance.create', $invitation), 180);

        return view('dashboard.invitations.show', compact('invitation', 'rsvps', 'stats', 'attendances', 'attendanceQr'));
    }

    public function edit(Invitation $myInvitation)
    {
        return view('dashboard.invitations.edit', ['invitation' => $myInvitation]);
    }

    public function update(SaveInvitationRequest $request, Invitation $myInvitation): RedirectResponse
    {
        $myInvitation->update([
            ...$this->withUploads($request, $myInvitation),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('dashboard.invitations.show', $myInvitation)
            ->with('success', 'Undangan berhasil diperbarui.');
    }

    public function destroy(Invitation $myInvitation): RedirectResponse
    {
        $files = [
            ...array_map(fn ($field) => $myInvitation->$field, array_keys(self::UPLOADS)),
            ...$myInvitation->galleries()->pluck('image_path'),
        ];
        Storage::disk('public')->delete(array_filter($files));

        // RSVP, ucapan, galeri, dan daftar hadir ikut terhapus lewat cascadeOnDelete
        $myInvitation->delete();

        return redirect()->route('dashboard')->with('success', 'Undangan berhasil dihapus.');
    }

    /**
     * Data tervalidasi dengan file yang sudah disimpan.
     * File lama dihapus bila diganti; dibiarkan bila tidak ada unggahan baru.
     */
    private function withUploads(SaveInvitationRequest $request, ?Invitation $invitation = null): array
    {
        $data = $request->validated();

        foreach (self::UPLOADS as $field => $folder) {
            unset($data[$field]);

            if ($file = $request->file($field)) {
                if ($invitation?->$field) {
                    Storage::disk('public')->delete($invitation->$field);
                }
                $data[$field] = $file->store($folder, 'public');
            }
        }

        return $data;
    }
}
