<?php

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
        	[
        		'name'			=> 'Product 1',
        		'description'	=> NULL,
        		'price'			=> '100.00'
        	],
        	[
        		'name'			=> 'Product 2',
        		'description'	=> NULL,
        		'price'			=> '399.99'
        	],
        	[
        		'name'			=> 'Product 3',
        		'description'	=> NULL,
        		'price'			=> '21.00'
        	],
        ];

        foreach ($products as $product) {
        	Product::create($product);
        }
    }
}
