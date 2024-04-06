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
        // there are two users with id 1 and 2
        // each user can have many inventories
        // each inventory can have many items
        // no inventory can have same name inside the same user
        // no two items can have the same name inside the same inventory
        // create 5 inventories with 20 items each for each user

        $users = User::all();

        foreach ($users as $user) {

            $inventories = Inventory::factory()->count(5)->create([
                'user_id' => $user->id
            ]);

            foreach ($inventories as $inventory) {

                Item::factory()->count(20)->create([
                    'inventory_id' => $inventory->id
                ]);
            }
        }
    }
}
