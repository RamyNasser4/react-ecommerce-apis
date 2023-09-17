<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Color;
use App\Models\Category;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    public function colors(){
        return $this->belongsToMany(Color::class);
    }
    public function categories(){
        return $this->belongsToMany(Category::class);
    }
}
