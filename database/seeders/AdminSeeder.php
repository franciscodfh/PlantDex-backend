<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::where('username', 'FranciscoCid')->update(['is_admin' => true]);
    }
}
