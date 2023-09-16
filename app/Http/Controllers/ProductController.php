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
            if(!str_starts_with($product->image,"http")){
                $product->image = "data:image/png;base64,". base64_encode(Storage::get("products/".$product->image));
            }
        }
        return response(['products' => $products,
                         'colors' => $colors],201);
    }
    public function recommended(){
        $recommendedproducts = Category::where('category_name','Recommended')->first()->products;
        foreach($recommendedproducts as $product){
            if(!str_starts_with($product->image,"http")){
                $product->image = "data:image/png;base64,". base64_encode(Storage::get("products/".$product->image));
            }
        }
        return response($recommendedproducts,201);
    }
    public function featured(){
        $featuredproducts = Category::where('category_name','Featured')->first()->products;
        foreach($featuredproducts as $product){
            if(!str_starts_with($product->image,"http")){
                $product->image = "data:image/png;base64,". base64_encode(Storage::get("products/".$product->image));
            }
        }
        return response($featuredproducts,201);
    }
    public function product($id){
        $product = Product::find($id);
        if(!str_starts_with($product->image,"http")){
            $product->image = ["data:image/png;base64,". base64_encode(Storage::get("products/".$product->image))];
        }else{
            $product->image = explode(",",$product->image);
        }
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
    public function editProduct($id,Request $request){
        $product = Product::find($id);
        if($request->name){
            $product->name = $request->name;
        }
        if($request->collection_name){
            $product->collection_name = $request->collection_name;
        }
        if($request->image){
            $imagepath = Storage::putFile('products',$request->image);
            $product->image = explode("/",$imagepath)[1];
        }
        if($request->price){
            $product->price = $request->price;
        }
        if($request->details){
            $product->details = $request->details;
        }
        $product->save();
        if($request->colors){
            $request->colors = json_decode($request->colors);
            foreach($request->colors as $color){
                $colorproduct = ColorProduct::where('color_id',$color)->where('product_id',$product->id)->exists();
                if($colorproduct === false){
                    $colorproduct = new ColorProduct();
                    $colorproduct->product_id = $product->id;
                    $colorproduct->color_id = $color;
                    $colorproduct->save();
                }
            }
        }
        $colors = Product::where('id',$product->id)->first()->colors;
        $response = ['product' => $product,
                      'colors' => $colors];
        return response($response);              
    }
    public function deleteProduct($id){
        return Product::destroy($id);
    }
}
