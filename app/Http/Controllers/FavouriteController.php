<?php

namespace App\Http\Controllers;

use App\Models\Favourite;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    public function addOrRemoveFavourite($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found',
            ], 404);
        }


        $user = auth()->user();

        $favouriteProduct = $user->favourites()->where('product_id', $id)->first();

        if (!$favouriteProduct) {
            Favourite::create([
                'user_id' => $user->id,
                'product_id' => $id,
            ]);

            return response()->json([
                'message' => 'Product added to favourites',
                'product_id' => $id,
            ], 200);
        }

        $favouriteProduct->delete();

        return response()->json([
            'message' => 'Product removed from favourites',
        ], 200);
    }
}
