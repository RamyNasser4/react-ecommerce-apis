<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function products(){
        $products = Product::all();
        $colors = array();
        foreach($products as $product){
            array_push($colors,$product->colors->first());
        }
        return response(['products' => $products,
                         'colors' => $colors],201);
    }
    public function recommended(){
        $recommendedproducts = Category::where('category_name','Recommended')->first()->products;
        return response($recommendedproducts,201);
    }
    public function featured(){
        $featuredproducts = Category::where('category_name','Featured')->first()->products;
        return response($featuredproducts,201);
    }
    public function product($id){
        $product = Product::find($id);
        $colors = Product::where('id',$id)->first()->colors;
        $response = ['product' => $product,
                     'colors' =>$colors];
        return response($response,201);
    }
}
