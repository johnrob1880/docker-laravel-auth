<?php

use Illuminate\Database\Seeder;

class ProductFeaturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Omega-3 Index Feature

        DB::table('product_features')->insert([
            'product_id' => 1, // Basic
            'feature_id' => 1
        ]);

        DB::table('product_features')->insert([
            'product_id' => 2, // Plus
            'feature_id' => 1
        ]);

        DB::table('product_features')->insert([
            'product_id' => 3, // Complete
            'feature_id' => 1
        ]);

        // Omega-6:Omega-3 Ratio Feature

        DB::table('product_features')->insert([
            'product_id' => 2, // Plus
            'feature_id' => 2
        ]);

        DB::table('product_features')->insert([
            'product_id' => 3, // Complete
            'feature_id' => 2
        ]);

        // AA:EPA Ratio Feature

        DB::table('product_features')->insert([
            'product_id' => 2, // Plus
            'feature_id' => 3
        ]);

        DB::table('product_features')->insert([
            'product_id' => 3, // Complete
            'feature_id' => 3
        ]);

        // Trans Fat Index Feature

        DB::table('product_features')->insert([
            'product_id' => 2, // Plus
            'feature_id' => 4
        ]);

        DB::table('product_features')->insert([
            'product_id' => 3, // Complete
            'feature_id' => 4
        ]);

        // Full Fatty Acid Profile Feature
        DB::table('product_features')->insert([
            'product_id' => 3, // Complete
            'feature_id' => 5
        ]);

        // Personalized dietary recommendations
        DB::table('product_features')->insert([
            'product_id' => 1, // Basic
            'feature_id' => 6
        ]);

        DB::table('product_features')->insert([
            'product_id' => 2, // Plus
            'feature_id' => 6
        ]);

        DB::table('product_features')->insert([
            'product_id' => 3, // Complete
            'feature_id' => 6
        ]);

        // Fatty Acid Research Report
        DB::table('product_features')->insert([
            'product_id' => 3, // Complete
            'feature_id' => 7
        ]);

        // EPA+DHA content of commonly consumed seafood
        DB::table('product_features')->insert([
            'product_id' => 1, // Basic
            'feature_id' => 8
        ]);

        DB::table('product_features')->insert([
            'product_id' => 2, // Plus
            'feature_id' => 8
        ]);

        DB::table('product_features')->insert([
            'product_id' => 3, // Complete
            'feature_id' => 8
        ]);

        // Trans fat content of commonly consumed food
        DB::table('product_features')->insert([
            'product_id' => 2, // Plus
            'feature_id' => 9
        ]);

        DB::table('product_features')->insert([
            'product_id' => 3, // Complete
            'feature_id' => 9
        ]);

    }
}
