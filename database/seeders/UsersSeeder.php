<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user=new User();
        $user->name="admin";
        $user->phone_number="0935242956";
        $user->email="ethar2001@gmail.com";
        $user->type="admin";
        $user->confirmed=true;
        $user->password=bcrypt("12345");
        $user->image="av1.png";
        $user->save();


        //
    }
}
