<?php
// FILE: app/Http/Requests/SaveInvitationRequest.php

namespace App\Http\Requests;

use App\Models\Invitation;
use App\Services\GoogleDriveAlbum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveInvitationRequest extends FormRequest
{
    // Kepemilikan sudah dijamin oleh binding route {myInvitation}
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $rules = [
            'theme'             => 'required|string|in:' . implode(',', array_keys(\App\Models\Invitation::AVAILABLE_THEMES)),
            'groom_name'        => 'required|string|max:255',
            'groom_full_name'   => 'required|string|max:255',
            'groom_father'      => 'required|string|max:255',
            'groom_mother'      => 'required|string|max:255',
            'groom_photo'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'bride_name'        => 'required|string|max:255',
            'bride_full_name'   => 'required|string|max:255',
            'bride_father'      => 'required|string|max:255',
            'bride_mother'      => 'required|string|max:255',
            'bride_photo'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'akad_date'         => 'required|date',
            'akad_location'     => 'required|string|max:255',
            'akad_address'      => 'required|string',
            'reception_date'    => 'required|date',
            'reception_location'=> 'required|string|max:255',
            'reception_address' => 'required|string',
            'maps_lat'          => 'nullable|numeric|between:-90,90',
            'maps_lng'          => 'nullable|numeric|between:-180,180',
            'music_file'        => 'nullable|mimes:mp3,wav|max:5120',
            'hero_image'        => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
            'drive_folder_url'  => ['nullable', 'string', 'max:500', 'regex:' . GoogleDriveAlbum::URL_PATTERN],
        ];

        // Saat mengedit, pemilik boleh mengganti alamat (slug) undangannya
        if ($invitation = $this->route('myInvitation')) {
            $rules['slug'] = [
                'required', 'string', 'max:100',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::notIn(Invitation::RESERVED_SLUGS),
                Rule::unique('invitations', 'slug')->ignore($invitation->id),
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'required'     => 'Kolom :attribute wajib diisi.',
            'image'        => 'Kolom :attribute harus berupa gambar.',
            'mimes'        => 'Format file :attribute tidak didukung.',
            'max'          => [
                'file'   => 'Ukuran file :attribute terlalu besar.',
                'string' => 'Kolom :attribute terlalu panjang.',
            ],
            'date'         => 'Kolom :attribute harus berupa tanggal yang valid.',
            'numeric'      => 'Kolom :attribute harus berupa angka.',
            'between'      => 'Nilai :attribute di luar rentang yang diperbolehkan.',
            'slug.regex'   => 'Alamat undangan hanya boleh berisi huruf kecil, angka, dan tanda hubung.',
            'slug.not_in'  => 'Alamat undangan ini tidak dapat dipakai.',
            'slug.unique'  => 'Alamat undangan ini sudah dipakai undangan lain.',
            'drive_folder_url.regex' => 'Link album harus berupa link folder Google Drive (https://drive.google.com/drive/folders/...).',
            'theme.required' => 'Pilih tema untuk undangan.',
            'theme.in'       => 'Tema yang dipilih tidak tersedia.',
        ];
    }

    public function attributes(): array
    {
        return [
            'theme'              => 'tema',
            'groom_name'         => 'nama panggilan mempelai pria',
            'groom_full_name'    => 'nama lengkap mempelai pria',
            'groom_father'       => 'nama ayah mempelai pria',
            'groom_mother'       => 'nama ibu mempelai pria',
            'groom_photo'        => 'foto mempelai pria',
            'bride_name'         => 'nama panggilan mempelai wanita',
            'bride_full_name'    => 'nama lengkap mempelai wanita',
            'bride_father'       => 'nama ayah mempelai wanita',
            'bride_mother'       => 'nama ibu mempelai wanita',
            'bride_photo'        => 'foto mempelai wanita',
            'akad_date'          => 'tanggal akad',
            'akad_location'      => 'tempat akad',
            'akad_address'       => 'alamat akad',
            'reception_date'     => 'tanggal resepsi',
            'reception_location' => 'tempat resepsi',
            'reception_address'  => 'alamat resepsi',
            'maps_lat'           => 'latitude',
            'maps_lng'           => 'longitude',
            'music_file'         => 'musik latar',
            'hero_image'         => 'foto sampul',
            'drive_folder_url'   => 'link album Google Drive',
            'slug'               => 'alamat undangan',
        ];
    }
}
