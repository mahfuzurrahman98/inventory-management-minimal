<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Inventory;
use App\Models\Item;

class DataSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // crate two users
        User::create([
            'name' => 'Arif',
            'email' => 'arif@gmail.com',
            'password' => bcrypt('arifPass')
        ]);
        User::create([
            'name' => 'Reza',
            'email' => 'reza@gmail.com',
            'password' => bcrypt('rezaPass')
        ]);

        $users = User::all();

        foreach ($users as $user) {
            // create 7 inventories for each user
            $inventories = Inventory::factory()->count(7)->create([
                'user_id' => $user->id
            ]);

            // create 18 items for each inventory
            foreach ($inventories as $inventory) {
                Item::factory()->count(18)->create([
                    'inventory_id' => $inventory->id
                ]);
            }
        }
    }
}
