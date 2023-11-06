<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UsersSeeder;
//use Database\Seeders\MobilesTableSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @return void
     */
    public function run(): void
    {

      //  \App\Models\User::factory(2)->create();
        $this->call([
            UsersSeeder::class,
          //  MobilesTableSeeder::class
        ]);
    }
}
