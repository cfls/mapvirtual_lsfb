<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Creates (or updates) the first admin account. There is no public
     * registration route on purpose — accounts are only ever created
     * this way (or manually in tinker/TablePlus), so access to
     * /admin/lieux stays limited to people the team explicitly adds.
     *
     * IMPORTANT: change this email/password before running in production,
     * then change the password again after first login.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@cfls.be'],
            [
                'name' => 'Administrateur CFLS',
                'password' => Hash::make('1981@Evere'),
                'email_verified_at' => now(),
            ]
        );

        $this->command?->info('Compte admin pret : admin@cfls.be / 1981@Evere — CHANGEZ CE MOT DE PASSE.');
    }
}