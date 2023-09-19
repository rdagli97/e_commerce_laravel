<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public function createImage(Request $request, $id)
    {

        $product = Product::find($id);

        if ($product->user_id != auth()->user()->id) {
            return response()->json([
                'message' => 'You do not have a permission',
            ], 403);
        }

        $request->validate([
            'image' => 'required|string',
        ]);

        $image = $this->saveImage($request->image, 'products');

        $product->update([
            'image' => $image,
        ]);

        $productImage = ProductImage::create([
            'image' => $image,
            'product_id' => $product->id,
        ]);

        return response()->json([
            'message' => 'Photo added successfully',
            'productImage' => $productImage,
        ], 200);
    }
}
