<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\BundleProduct;
use App\Models\Product;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    private $toSelect = ['id', 'name', 'weight', 'stock', 'price'];

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

        $products = collect($products)->mapWithKeys(function($product, $index) use ($quantities) {
            return [$product => [
                    'quantity'  => $quantities[$index]
                ],
            ];
        })->all();

        $bundle->products()->attach($products);

        return response()->json(
            ["message" => "Bundle created successfully"]
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
        if ($bundle == null) {
            return response()->json(
                ["message" => "Bundle not found"]
            );
        }


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

        $products = collect($products)->mapWithKeys(function($product, $index) use ($quantities) {
            return [$product => [
                    'quantity'  => $quantities[$index]
                ],
            ];
        })->all();

        $bundle->products()->sync($products);

        return response()->json(
            ["message" => "Bundle updated successfully"]
        );
    }

    public function delete(Request $req)
    {
        $bundle = Bundle::find($req->id);
        if ($bundle == null) {
            return response()->json(
                ["message" => "Bundle not found"]
            );
        }

        $bundle->delete();

        return response()->json(
            ["message" => "Bundle deleted successfully"]
        );
    }

    public function all()
    {
        $bundles = Bundle::select($this->toSelect)
            ->with('products')
            ->paginate(5);
        return response()->json([
            "data" => $bundles
        ]);
    }

    public function get(Request $req)
    {
        $bundle = Bundle::find($req->id, $this->toSelect)
            ->with('products');
        return response()->json([
            "data" => $bundle
        ]);
    }
}
