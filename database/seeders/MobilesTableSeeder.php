<?php

namespace Database\Seeders;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MobilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $products = [
            [
                'name' => 'grand',
                'model' => 'g250',
                'specification' => '200.10',
                'image' => 'https://assets.myntassets.com/h_1440,q_100,w_1080/v1/assets/images/1038959/2015/12/7/11449479796511-INVICTUS-Red--Navy-Checked-Slim-Formal-Shirt-4621449479796242-3.jpg',
                'company' => 'new DateTime',
                'price' => 123,
                'quantity'=>3,
            ],
            [
                'name' => 'grand1',
                'model' => 'g2511',
                'specification' => '200.101',
                'image' => 'https://assets.myntassets.com/h_1440,q_100,w_1080/v1/assets/images/1038959/2015/12/7/11449479796511-INVICTUS-Red--Navy-Checked-Slim-Formal-Shirt-4621449479796242-3.jpg',
                'company' => 'new DateTime1',
                'price' => 1231,
                'quantity'=>1,
            ],
            [
                'name' => 'grand2',
                'model' => 'g2502',
                'specification' => '200.10',
                'image' => 'https://assets.myntassets.com/h_1440,q_100,w_1080/v1/assets/images/1038959/2015/12/7/11449479796511-INVICTUS-Red--Navy-Checked-Slim-Formal-Shirt-4621449479796242-3.jpg',
                'company' => 'new DateTime',
                'price' => 123,
                'quantity'=>3,
            ],
        ];

        DB::table('mobiles')->insert($products);

    }
}
