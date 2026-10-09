<?php

namespace Tests;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    // Data undangan lengkap untuk form maupun Invitation::create()
    protected function invitationData(array $override = []): array
    {
        return [
            'theme'              => 'netflix',
            'groom_name'         => 'Desi',
            'groom_full_name'    => 'Muhammad Desi Pratama',
            'groom_father'       => 'Ahmad Fauzi',
            'groom_mother'       => 'Siti Rahayu',
            'bride_name'         => 'Zaky',
            'bride_full_name'    => 'Nur Zaky Maharani',
            'bride_father'       => 'Hendra Wijaya',
            'bride_mother'       => 'Dewi Lestari',
            'akad_date'          => '2027-09-20T08:00',
            'akad_location'      => 'Masjid Al-Ikhlas',
            'akad_address'       => 'Jl. Melati No. 10, Cilacap',
            'reception_date'     => '2027-09-20T11:00',
            'reception_location' => 'Gedung Serbaguna Cilacap',
            'reception_address'  => 'Jl. Gatot Subroto No. 45, Cilacap',
            ...$override,
        ];
    }

    protected function invitationFor(?User $user = null, array $override = []): Invitation
    {
        return Invitation::create($this->invitationData(['user_id' => $user?->id, ...$override]));
    }
}
