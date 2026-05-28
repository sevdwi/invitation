<?php
// FILE: app/Http/Controllers/RsvpController.php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRsvpRequest;
use App\Models\Invitation;
use App\Models\Rsvp;
use Illuminate\Http\JsonResponse;

class RsvpController extends Controller
{
    public function store(StoreRsvpRequest $request): JsonResponse
    {
        $invitation = Invitation::where('is_active', true)->firstOrFail();

        Rsvp::create([
            ...$request->validated(),
            'invitation_id' => $invitation->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Konfirmasi kehadiran berhasil dikirim! Terima kasih. 🙏',
        ]);
    }
}