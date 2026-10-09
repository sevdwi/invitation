<?php
// FILE: app/Http/Requests/StoreAttendanceRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'guest_name'  => 'required|string|max:100',
            'guest_phone' => 'nullable|string|max:20',
            'address'     => 'nullable|string|max:255',
            'guest_count' => 'required|integer|min:1|max:10',
        ];
    }

    public function messages(): array
    {
        return [
            'guest_name.required'  => 'Nama wajib diisi.',
            'guest_name.max'       => 'Nama terlalu panjang.',
            'guest_phone.max'      => 'Nomor WhatsApp terlalu panjang.',
            'address.max'          => 'Alamat / instansi terlalu panjang.',
            'guest_count.required' => 'Jumlah orang wajib diisi.',
            'guest_count.integer'  => 'Jumlah orang harus berupa angka.',
            'guest_count.min'      => 'Minimal 1 orang.',
            'guest_count.max'      => 'Maksimal 10 orang.',
        ];
    }
}
