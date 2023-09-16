<?php

namespace App\Http\Controllers;

use App\Http\Requests\addProductRequest;
use App\Models\Category;
use App\Models\ColorProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
    public function getProductCount(){
        $products = Product::all();
        return response(sizeof($products),201);
    }
    public function newproduct(addProductRequest $request){
        $product = new Product();
        $product->name = $request->name;
        $imagepath = Storage::putFile('products',$request->image);
        $product->image = explode("/",$imagepath)[1];
        $product->collection_name = $request->collection_name;
        $product->price = $request->price;
        $product->details = $request->details;
        $product->save();
        if($request->colors){
            foreach($request->colors as $color){
                $colorproduct = new ColorProduct();
                $colorproduct->product_id = $product->id;
                $colorproduct->color_id = $color;
                $colorproduct->save();
            }
        }
        $colors = Product::where('id',$product->id)->first()->colors;
        $response = ['product' => $product,
                      'colors' => $colors];
        return response($response);
    }
    public function getProductImg($imagepath){
        $image = Storage::get("products/".$imagepath);
        $img = base64_encode($image);
        return response("data:image/png;base64,".$img);
    }
}
