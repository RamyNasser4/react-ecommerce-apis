<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function colors(){
        $colors = Color::all();
        return response(['colors' => $colors],201);
    }
}
