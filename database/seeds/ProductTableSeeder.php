<?php

use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'product_id' => 'f7446c99-2703-4140-9d07-4bbda5a522d6',
            'name' => 'Omega-3 Index Basic (O3i)',
            'product_group' => 'Test Kits',
            'product_index' => 1
        ]);

        DB::table('products')->insert([
            'product_id' => 'ce75f71c-c593-45f3-9acc-7b3c34c61929',
            'name' => 'Omega-3 Index Plus (O3i)',
            'product_group' => 'Test Kits',
            'product_index' => 2
        ]);

        DB::table('products')->insert([
            'product_id' => '0342b4a8-9814-43fe-a803-88f1f613bb38',
            'name' => 'Omega-3 Index Complete (O3i)',
            'product_group' => 'Test Kits',
            'product_index' => 2
        ]);
    }
}
