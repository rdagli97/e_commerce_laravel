<?php

namespace App\Http\Controllers;

use App\Models\BuyedProducts;
use App\Models\Product;
use App\Models\SaledProduct;
use Illuminate\Http\Request;

class BuyedProductsController extends Controller
{
    public function createBuyedProduct(Request $request, $id)
    {
        $product = Product::find($id);

        $attrs = $request->validate([
            'piece' => 'required|numeric',
        ]);

        $user = auth()->user();

        if ($user->role === 0) {
            $buyedProduct = BuyedProducts::create([
                'user_id' => auth()->user()->id,
                'title' => $product->title,
                'description' => $product->description,
                'price' => $product->price * $attrs['piece'],
                'piece' => $attrs['piece'],
                'image' => $product->image,
            ]);

            $saledProduct = SaledProduct::create([
                'user_id' => $product->user_id,
                'title' => $product->title,
                'description' => $product->description,
                'piece' => $attrs['piece'],
                'price' => $product->price * $attrs['piece'],
                'image' => $product->image,
            ]);

            return response()->json([
                'buyedProduct' => $buyedProduct,
                'saledProduct' => $saledProduct,
            ], 200);
        } else {
            return response()->json([
                'message' => 'You do not have a permission for buy something',
            ], 403);
        }
    }

    public function getMyBuyedProducts()
    {

        $buyedProducts = BuyedProducts::where('user_id', auth()->user()->id)->get();

        if (!$buyedProducts) {
            return response()->json([
                'message' => 'There is no buyed product',
            ], 404);
        }

        return response()->json([
            'buyed_products' => $buyedProducts,
        ], 200);
    }
}
