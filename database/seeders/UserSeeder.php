<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(1000)
            ->create()
            ->each(function (User $user): void {
                Order::factory(random_int(1, 5))
                    ->create([
                        'user_id' => $user->id,
                    ]);
            });
    }
}
