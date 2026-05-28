<?php
// FILE: app/Http/Requests/StoreRsvpRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRsvpRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'guest_name'  => 'required|string|max:100',
            'guest_phone' => 'nullable|string|max:20',
            'attendance'  => 'required|in:hadir,tidak_hadir,belum_pasti',
            'guest_count' => 'required|integer|min:1|max:10',
            'note'        => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'guest_name.required'  => 'Nama tamu wajib diisi.',
            'attendance.required'  => 'Konfirmasi kehadiran wajib dipilih.',
            'guest_count.required' => 'Jumlah tamu wajib diisi.',
            'guest_count.min'      => 'Minimal 1 tamu.',
            'guest_count.max'      => 'Maksimal 10 tamu.',
        ];
    }
}