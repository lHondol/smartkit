<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $toSelect = ['id', 'name', 'price', 'weight', 'stock'];

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
            ["message" => "Product created successfully"]
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
                ["message" => "Product not found"]
            );
        }

        $product->name = $req->name;
        $product->price = $req->price;
        $product->weight = $req->weight;
        $product->stock = $req->stock;
        $product->save();

        return response()->json(
            ["message" => "Product updated successfully"]
        );
    }

    public function delete(Request $req)
    {
        $product = Product::find($req->id);
        if ($product == null) {
            return response()->json(
                ["message" => "Product not found"]
            );
        }

        $product->delete();

        return response()->json(
            ["message" => "Product deleted successfully"]
        );
    }

    public function all()
    {
        $products = Product::select($this->toSelect)
        ->paginate(10);
        return response()->json([
            "data" => $products
        ]);
    }

    public function get(Request $req)
    {
        $product = Product::find($req->id)
        ->select($this->toSelect)
        ->first();
        return response()->json([
            "data" => $product
        ]);
    }
}
