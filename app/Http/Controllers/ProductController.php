<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function products(){
        $products = Product::all();
        return response($products,201);
    }
    public function recommended(){
        $recommendedproducts = Category::where('category_name','Recommended')->first()->products;
        return response($recommendedproducts,201);
    }
    public function featured(){
        $featuredproducts = Category::where('category_name','Featured')->first()->products;
        return response($featuredproducts,201);
    }
}
