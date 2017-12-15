<?php

use Illuminate\Database\Seeder;

class FeatureTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('features')->insert([
            'feature_category' => 'Products',
            'feature_group' => 'Test Kits',
            'title' => 'Omega-3 Index',            
            'feature_index' => 1
        ]);

        DB::table('features')->insert([
            'feature_category' => 'Products',
            'feature_group' => 'Test Kits',
            'title' => 'Omega-6:Omega-3 Ratio',            
            'feature_index' => 2
        ]);

        DB::table('features')->insert([
            'feature_category' => 'Products',
            'feature_group' => 'Test Kits',
            'title' => 'AA/EPA Ratio',            
            'feature_index' => 3
        ]);

        DB::table('features')->insert([
            'feature_category' => 'Products',
            'feature_group' => 'Test Kits',
            'title' => 'Trans Fat Index',            
            'feature_index' => 4
        ]);
        
        DB::table('features')->insert([
            'feature_category' => 'Products',
            'feature_group' => 'Test Kits',
            'title' => 'Full Fatty Acid Profile',            
            'feature_index' => 5
        ]);

        DB::table('features')->insert([
            'feature_category' => 'Products',
            'feature_group' => 'Test Kits',
            'title' => 'Personalized dietary recommendations',            
            'feature_index' => 6
        ]);

        DB::table('features')->insert([
            'feature_category' => 'Products',
            'feature_group' => 'Test Kits',
            'title' => 'Fatty Acid Research Report',            
            'feature_index' => 7
        ]);

        DB::table('features')->insert([
            'feature_category' => 'Products',
            'feature_group' => 'Test Kits',
            'title' => 'EPA+DHA content of commonly consumed seafood',            
            'feature_index' => 8
        ]);

        DB::table('features')->insert([
            'feature_category' => 'Products',
            'feature_group' => 'Test Kits',
            'title' => 'Trans fat content of commonly consumed food',            
            'feature_index' => 8
        ]);

    }
}
