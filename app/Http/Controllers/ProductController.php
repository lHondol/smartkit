<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function add(Request $req)
    {
        $req->validate([
            'name' => 'required',
            'price' => 'required',
            'weight' => 'required',
            'stock' => 'required'
        ]);

        $product = new Product();
        $product->id = Uuid::uuid();
        $product->name = $req->name;
        $product->price = $req->price;
        $product->weight = $req->weight;
        $product->stock = $req->stock;
        $product->save();

        return response()->json(
            ["message" => "product created"]
        );
    }

    public function update(Request $req)
    {
        $req->validate([
            'name' => 'required',
            'price' => 'required',
            'weight' => 'required',
            'stock' => 'required'
        ]);

        $product = Product::find($req->id);
        if ($product == null) {
            return response()->json(
                ["message" => "product not found"]
            );
        }

        $product->name = $req->name;
        $product->price = $req->price;
        $product->weight = $req->weight;
        $product->stock = $req->stock;
        $product->save();

        return response()->json(
            ["message" => "product updated"]
        );
    }

    public function delete(Request $req)
    {
        $product = Product::find($req->id);
        if ($product == null) {
            return response()->json(
                ["message" => "product not found"]
            );
        }

        $product->delete();

        return response()->json(
            ["message" => "product deleted"]
        );
    }

    public function all()
    {
        $products = Product::all();
        return response()->json([
            "data" => $products
        ]);
    }

    public function get(Request $req)
    {
        $product = Product::find($req->id);
        return response()->json([
            "data" => $product
        ]);
    }
}
