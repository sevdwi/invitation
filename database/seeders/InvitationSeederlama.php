<?php
// FILE: database/seeders/InvitationSeeder.php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\Invitation;
use App\Models\Wish;
use Illuminate\Database\Seeder;

class InvitationSeeder extends Seeder
{
    public function run(): void
    {
        Invitation::truncate();

        $invitation = Invitation::create([
            'groom_name'         => 'Desi',
            'groom_full_name'    => 'Muhammad Desi Pratama, S.T.',
            'groom_father'       => 'Ahmad Fauzi',
            'groom_mother'       => 'Siti Rahayu',
            'groom_photo'        => null,

            'bride_name'         => 'Zaky',
            'bride_full_name'    => 'Nur Zaky Maharani, S.Pd.',
            'bride_father'       => 'Hendra Wijaya',
            'bride_mother'       => 'Dewi Lestari',
            'bride_photo'        => null,

            'akad_date'          => '2025-09-20 08:00:00',
            'akad_location'      => 'Masjid Al-Ikhlas',
            'akad_address'       => 'Jl. Melati No. 10, Cilacap, Jawa Tengah',

            'reception_date'     => '2025-09-20 11:00:00',
            'reception_location' => 'Gedung Serbaguna Cilacap',
            'reception_address'  => 'Jl. Gatot Subroto No. 45, Cilacap, Jawa Tengah',

            'maps_lat'           => -7.7126,
            'maps_lng'           => 109.0138,
            'music_file'         => null,
            'hero_image'         => null,
            'is_active'          => true,
        ]);

        $captions = [
            'Momen berharga bersama', 'Kenangan indah kita',
            'Perjalanan cinta',       'Bersama selamanya',
            'Senyum bahagia',         'Cinta abadi',
        ];

        foreach ($captions as $i => $caption) {
            Gallery::create([
                'invitation_id' => $invitation->id,
                'image_path'    => 'gallery/photo' . ($i + 1) . '.jpg',
                'caption'       => $caption,
                'order'         => $i + 1,
            ]);
        }

        $wishes = [
            ['sender_name' => 'Keluarga Besar Santoso', 'message' => 'Semoga menjadi keluarga sakinah, mawaddah, warahmah. Barakallahu lakuma wa baraka alaikuma. Aamiin 🤲'],
            ['sender_name' => 'Rini & Andi',             'message' => 'Selamat menempuh hidup baru! Semoga selalu bahagia dan saling menguatkan. 💕'],
            ['sender_name' => 'Rekan-rekan Kantor',      'message' => 'Bahagia selalu! Semoga rumah tangga penuh cinta dan keberkahan. 🎊'],
        ];

        foreach ($wishes as $wish) {
            Wish::create([
                ...$wish,
                'invitation_id' => $invitation->id,
                'is_approved'   => true,
            ]);
        }

        $this->command->info('✅ Data undangan berhasil dibuat!');
    }
}
