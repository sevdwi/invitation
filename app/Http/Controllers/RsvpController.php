<?php
// FILE: app/Http/Controllers/RsvpController.php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRsvpRequest;
use App\Models\Invitation;
use Illuminate\Http\JsonResponse;

class RsvpController extends Controller
{
    public function store(StoreRsvpRequest $request, Invitation $invitation): JsonResponse
    {
        $invitation->rsvps()->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Konfirmasi kehadiran berhasil dikirim! Terima kasih. 🙏',
        ]);
    }
}
