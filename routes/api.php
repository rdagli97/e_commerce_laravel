<?php


use App\Http\Controllers\BuyedProductsController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentImageController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\SaledProductController;
use App\Http\Controllers\UserController;
use App\Models\Comment;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register/customer', [UserController::class, 'registerCustomer']);
Route::post('/register/company', [UserController::class, 'registerCompany']);
Route::post('/login', [UserController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    // User
    Route::get('/user', [UserController::class, 'getCurrentUser']);
    Route::put('/user/update', [UserController::class, 'update']);
    Route::post('/user/logout', [UserController::class, 'logout']);

    // Product
    Route::post('/product/create', [ProductController::class, 'createProduct']);
    Route::delete('/product/{id}/delete', [ProductController::class, 'delete']);
    Route::put('/product/{id}/discount', [ProductController::class, 'giveDiscount']);
    Route::get('/products', [ProductController::class, 'getProducts']);
    Route::get('/my/products', [ProductController::class, 'getMyProducts']);
    Route::get('/discounted/products', [ProductController::class, 'discountedProducts']);
    Route::get('/products/favourites', [ProductController::class, 'getMyFavouriteProducts']);

    // Buyed Product
    Route::post('/buyed_product/{id}/create', [BuyedProductsController::class, 'createBuyedProduct']);
    Route::get('/buyed_products', [BuyedProductsController::class, 'getMyBuyedProducts']);

    // Saled Product
    Route::get('/saled_products', [SaledProductController::class, 'getMySaledProducts']);

    // favourite
    Route::post('/product/{id}/favourite', [FavouriteController::class, 'addOrRemoveFavourite']);

    // Product Image
    Route::post('/product/{id}/create/product_image', [ProductImageController::class, 'createImage']);

    // Comment
    Route::post('/product/{id}/comment/create', [CommentController::class, 'createComment']);
    Route::delete('/comments/{id}/delete', [CommentController::class, 'deleteComment']);
    Route::get('/user/comments', [CommentController::class, 'getMyComments']);

    // Comment image
    Route::post('/comment/{id}/create/comment_image', [CommentImageController::class, 'createImage']);
});
