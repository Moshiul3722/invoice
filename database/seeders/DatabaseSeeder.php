<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        User::create([
            'name' => 'Moshiul Alam',
            'email' => 'gazimoshiul@gmail.com',
            'company' => 'Gazi Soft Technology',
            'phone' => '+1 202-918-2132',
            'country' => 'Bbangladesh',
            'password' => bcrypt('12345678'),
            'thumbnail' => 'https://picsum.photos/300'
        ]);
        // User::create([
        //     'name'=>'Jon Doe',
        //     'email'=>'jondoe@gmail.com',
        //     'company'=>'Dhaka Tech Information',
        //     'phone'=>'+93 70 228 8238',
        //     'country'=>'Bbangladesh',
        //     'password'=>bcrypt('12345678'),
        //     'thumbnail'=>'https://picsum.photos/300'
        // ]);

        Client::factory(5)->create();

        Task::factory(50)->create();

        Invoice::factory(20)->create();
    }
}
