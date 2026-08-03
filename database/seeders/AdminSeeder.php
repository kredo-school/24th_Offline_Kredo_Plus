<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    protected User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->user = User::firstOrNew(['email' => 'admin@gmail.com']);

        $this->user->name = 'Administrator';
        $this->user->email = 'admin@gmail.com';
        $this->user->password = Hash::make('asdfasdf');
        $this->user->role_id = User::ADMIN_ROLE_ID;
        $this->user->save();
    }
}
