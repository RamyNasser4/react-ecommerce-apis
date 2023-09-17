<?php

namespace App\Http\Controllers;

use App\Http\Requests\addProductRequest;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\ColorProduct;
use App\Models\Product;
use App\Models\ProductCategory;
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
        $categories = Product::where('id',$id)->first()->categories;
        $response = ['product' => $product,
                     'colors' =>$colors,
                     'categories' => $categories];
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
        if($request->categories){
            foreach($request->categories as $category){
                $categoryproduct = new CategoryProduct();
                $categoryproduct->product_id = $product->id;
                $categoryproduct->category_id = $category;
                $categoryproduct->save();
            }
        }
        $colors = Product::where('id',$product->id)->first()->colors;
        $categories = Product::where('id',$product->id)->first()->categories;
        $response = ['product' => $product,
                      'colors' => $colors,
                      'categories' => $categories];
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
            $colorproducts = ColorProduct::where('product_id',$product->id)->get();
            foreach($colorproducts as $colorproduct){
                $exists = false;
                foreach($request->colors as $color){
                    if($colorproduct->color_id == $color){
                        $exists = true;
                    }
                }
                if(!$exists){
                    $colorproduct->delete();
                }
            }
        }
        if($request->categories){
            $request->categories = json_decode($request->categories);
            foreach($request->categories as $category){
                $categoryproduct = CategoryProduct::where('category_id',$category)->where('product_id',$product->id)->exists();
                if($categoryproduct === false){
                    $categoryproduct = new CategoryProduct();
                    $categoryproduct->product_id = $product->id;
                    $categoryproduct->category_id = $category;
                    $categoryproduct->save();
                }
            }
            $categoryproducts = CategoryProduct::where('product_id',$product->id)->get();
            foreach($categoryproducts as $categoryproduct){
                $exists = false;
                foreach($request->categories as $category){
                    if($categoryproduct->category_id == $category){
                        $exists = true;
                    }
                }
                if(!$exists){
                    $categoryproduct->delete();
                }
            }
        }
        $colors = Product::where('id',$product->id)->first()->colors;
        $categories = Product::where('id',$product->id)->first()->categories;
        $response = ['product' => $product,
                      'colors' => $colors,
                      'categories' => $categories];
        return response($response);              
    }
    public function deleteProduct($id){
        return Product::destroy($id);
    }
}
