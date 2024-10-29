<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    // Get all products
    public function index()
    {
        return response()->json(Product::all());
        // return Product::all();
    }
}
