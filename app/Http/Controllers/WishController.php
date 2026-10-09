<?php
// FILE: app/Http/Controllers/WishController.php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWishRequest;
use App\Models\Invitation;
use Illuminate\Http\JsonResponse;

class WishController extends Controller
{
    public function store(StoreWishRequest $request, Invitation $invitation): JsonResponse
    {
        $wish = $invitation->wishes()->create([
            ...$request->validated(),
            'sender_ip' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ucapan berhasil dikirim! 💕',
            'data' => [
                'id'          => $wish->id,
                'sender_name' => $wish->sender_name,
                'message'     => $wish->message,
                'time_ago'    => 'Baru saja',
            ],
        ]);
    }

    public function index(Invitation $invitation): JsonResponse
    {
        $wishes = $invitation->wishes()
            ->where('is_approved', true)
            ->paginate(10);

        $wishes->getCollection()->transform(fn($w) => [
            'id'          => $w->id,
            'sender_name' => $w->sender_name,
            'message'     => $w->message,
            'time_ago'    => $w->created_at->diffForHumans(),
        ]);

        return response()->json([
            'success'  => true,
            'data'     => $wishes->items(),
            'has_more' => $wishes->hasMorePages(),
        ]);
    }
}
