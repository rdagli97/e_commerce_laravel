<?php

namespace App\Http\Controllers;

use App\Models\BuyedProducts;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function registerCustomer(Request $request)
    {
        $attrs = $request->validate([
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'image' => 'required|string',
            'phone' => 'required|string|unique:users,phone',
        ]);

        $image = $this->saveImage($request->image, 'profiles');

        $user = User::create([
            'username' => $attrs['username'],
            'email' => $attrs['email'],
            'password' => $attrs['password'],
            'image' => $image,
            'phone' => $attrs['phone'],
        ]);

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('secret')->plainTextToken,
        ], 200);
    }

    public function registerCompany(Request $request)
    {
        $attrs = $request->validate([
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone' => 'required|string|unique:users,phone',
        ]);

        $this->saveImage($request->image, 'profiles');

        $company = User::create([
            'username' => $attrs['username'],
            'email' => $attrs['email'],
            'password' => $attrs['password'],
            'phone' => $attrs['phone'],
            'role' => 1,
        ]);

        return response()->json([
            'company' => $company,
            'token' => $company->createToken('secret')->plainTextToken,
        ], 200);
    }

    public function login(Request $request)
    {

        $attrs = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (!Auth::attempt($attrs)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 403);
        }

        $user = User::find(auth()->user()->id);

        return response()->json([
            'message' => 'Login success',
            'user' => auth()->user(),
            'token' => $user->createToken('secret')->plainTextToken,
        ], 200);
    }

    public function logout()
    {
        $user = User::find(auth()->user()->id);

        $user->tokens()->delete();

        return response([
            'message' => 'Logout success.',
        ], 200);
    }

    public function update(Request $request)
    {
        $attrs = $request->validate([
            'image' => 'nullable|string',
            'adress' => 'nullable|string',
            'about_us' => 'nullable|string',
        ]);

        $user = User::find(auth()->user()->id);

        if (isset($attrs['image']) && !is_null($attrs['image']) && !empty($attrs['image'])) {
            // Update the image if it is not null or empty
            $user->image = $attrs['image'];
            $this->saveImage($request->image, 'profiles');
        }

        if (isset($attrs['adress'])) {
            // Update the address if it exists in the request
            $user->adress = $attrs['adress'];
        }

        if (isset($attrs['about_us'])) {
            // Update the about_us if it exists in the request
            $user->about_us = $attrs['about_us'];
        }

        $user->save();

        return response()->json([
            'message' => 'User updated',
            'user' => auth()->user(),
        ], 200);
    }

    public function getCurrentUser()
    {
        $user = User::where('id', auth()->user()->id)->with('products', function ($products) {
            return $products->where('user_id', auth()->user()->id)->with('productImages', 'comments')->get();
        })->with('buyedProducts', function ($buyedProducts) {
            return $buyedProducts->where('user_id', auth()->user()->id)->get();
        })->with('favourites', function ($favourites) {
            return $favourites->where('user_id', auth()->user()->id)->get();
        })->with('saledProducts', function ($saledProducts) {
            return $saledProducts->where('user_id', auth()->user()->id)->get();
        })->get();

        return response()->json([
            'user' => $user,
        ], 200);
    }
}
