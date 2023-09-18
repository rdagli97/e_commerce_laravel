<?php

namespace App\Http\Controllers;

use App\Models\SaledProduct;
use Illuminate\Http\Request;

class SaledProductController extends Controller
{
    public function getMySaledProducts()
    {
        $saledProducts = SaledProduct::where('user_id', auth()->user()->id)->get();

        if (!$saledProducts) {
            return response()->json([
                'message' => 'There is no saled product yet',
            ], 404);
        }

        return response()->json([
            'saledProducts' => $saledProducts,
        ], 200);
    }
}
