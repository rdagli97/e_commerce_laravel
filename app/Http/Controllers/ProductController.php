<?php

namespace App\Http\Controllers;

use App\Models\Favourite;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // create
    public function createProduct(Request $request)
    {
        $attrs = $request->validate([
            'title' => 'required|string|max:250',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
        ]);

        $user = auth()->user();

        if ($user->role === 1) {
            $product = Product::create([
                'user_id' => auth()->user()->id,
                'title' => $attrs['title'],
                'description' => $attrs['description'],
                'price' => $attrs['price'],
                'category' => $attrs['category'],
            ]);

            return response()->json([
                'message' => 'Product created successfully',
                'product' => $product,
            ], 200);
        } else {
            return response()->json([
                'message' => 'You do not have permission to create a product.',
            ], 403);
        }
    }

    // delete
    public function delete($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found',
            ], 404);
        }

        if ($product->user_id != auth()->user()->id) {
            return response()->json([
                'message' => 'You do not have permission to delete this post',
            ], 403);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product delete success',
        ], 200);
    }

    // update discount
    public function giveDiscount(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found',
            ], 404);
        }

        $attrs = $request->validate([
            'discount' => 'required|numeric|min:0|max:100',
        ]);

        if ($product->user_id != auth()->user()->id) {
            return response()->json([
                'message' => 'Permission denied',
            ], 403);
        }

        $product->update([
            'discount' => $attrs['discount'],
        ]);

        return response()->json([
            'message' => 'Product updated succesfully',
            'product' => $product,
        ], 200);
    }

    // get products
    public function getProducts()
    {

        $products = Product::with('productImages')
            ->with('comments', function ($comments) {
                return $comments->orderBy('created_at', 'desc')->with('comment_images')->get();
            })->get();


        return response()->json([
            'products' => $products,
        ], 200);
    }


    // get my products
    public function getMyProducts()
    {
        $products = Product::where('user_id', auth()->user()->id)
            ->with('comments', function ($comments) {
                return $comments->orderBy('created_at', 'desc')->with('comment_images')->get();
            })->with('productImages')->get();

        return response()->json([
            'products' => $products
        ], 200);
    }

    // get discounted products
    public function discountedProducts()
    {
        $products = Product::where('discount', '!=', 0)->get();

        return response()->json([
            'products' => $products,
        ], 200);
    }

    // get favourite products
    public function getMyFavouriteProducts()
    {
        $user = User::find(auth()->user()->id);

        $favouriteProductIds = $user->favourites()->pluck('product_id');

        $favouriteProducts = Product::whereIn('id', $favouriteProductIds)->orderBy('created_at', 'desc')
            ->with('favourites', function ($favourites) {
                return $favourites->where('user_id', auth()->user()->id)->select('user_id', 'product_id')->get();
            })->get();

        return response()->json([
            'favouriteProducts' => $favouriteProducts,
        ], 200);
    }
}
