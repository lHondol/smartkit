<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\BundleProduct;
use App\Models\Product;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    public function add(Request $req)
    {
        $req->validate([
            'name' => 'required',
            'weight' => 'required',
            'stock' => 'required',
            'products.*' => 'required',
            'quantities.*' => 'required'
        ]);

        $products = $req->products;
        $quantities = $req->quantities;

        $bundle = new Bundle();
        $bundle->id = Uuid::uuid();
        $bundle->name = $req->name;
        $bundle->weight = $req->weight;

        $calculated_price = 0;
        $min_stock = 2e9;
        for ($index = 0; $index < count($quantities); $index++) {
            $product = Product::find($products[$index]);
            $calculated_price += $product->price * $quantities[$index];

            if ($min_stock > $product->stock / $quantities[$index]) {
                $min_stock = $product->stock / $quantities[$index];
            }
        }

        $bundle->stock = $min_stock;
        $bundle->price = $calculated_price;
        $bundle->save();

        for ($index = 0; $index < count($products); $index++) {
            $bundle_product = new BundleProduct();
            $bundle_product->id = Uuid::uuid();
            $bundle_product->bundle_id = $bundle->id;
            $bundle_product->product_id = $products[$index];
            $bundle_product->quantity = $quantities[$index];
            $bundle_product->save();
        }


        return response()->json(
            ["message" => "bundle created"]
        );
    }

    public function update(Request $req)
    {
        $req->validate([
            'name' => 'required',
            'weight' => 'required',
            'stock' => 'required',
            'products.*' => 'required',
            'quantities.*' => 'required'
        ]);

        $products = $req->products;
        $quantities = $req->quantities;

        $bundle = Bundle::find($req->id);
        $bundle->id = Uuid::uuid();
        $bundle->name = $req->name;
        $bundle->weight = $req->weight;

        $calculated_price = 0;
        $min_stock = 2e9;
        for ($index = 0; $index < count($quantities); $index++) {
            $product = Product::find($products[$index]);
            $calculated_price += $product->price * $quantities[$index];

            if ($min_stock > $product->stock / $quantities[$index]) {
                $min_stock = $product->stock / $quantities[$index];
            }
        }

        $bundle->stock = $min_stock;
        $bundle->price = $calculated_price;
        $bundle->save();

        for ($index = 0; $index < count($products); $index++) {
            $bundle_product = new BundleProduct();
            $bundle_product->id = Uuid::uuid();
            $bundle_product->bundle_id = $bundle->id;
            $bundle_product->product_id = $products[$index];
            $bundle_product->quantity = $quantities[$index];
            $bundle_product->save();
        }


        return response()->json(
            ["message" => "bundle updated"]
        );
    }

    public function delete(Request $req)
    {
        $bundle = Bundle::find($req->id);
        if ($bundle == null) {
            return response()->json(
                ["message" => "bundle not found"]
            );
        }

        $bundle->delete();

        return response()->json(
            ["message" => "bundle deleted"]
        );
    }

    public function all()
    {
        $bundles = Bundle::select(['id', 'name', 'weight', 'stock', 'price'])
            ->with('products')
            ->get();
        return response()->json([
            "data" => $bundles
        ]);
    }

    public function get(Request $req)
    {
        $bundle = Bundle::find($req->id)
            ->select(['id', 'name', 'weight', 'stock', 'price'])
            ->with('products')->first();
        return response()->json([
            "data" => $bundle
        ]);
    }
}
