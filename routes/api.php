<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\HomeController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::Post('/store/contact',[ApiController::class,'storecontact']);
Route::get('/get/post/{id}',[ApiController::class,'getpostdata']);
Route::get('/get/slider/{id}',[ApiController::class,'getsliderimages']);
Route::get('/get/Newsletter',[ApiController::class,'getnewsletter']);
Route::get('/get/Pages/{id}',[ApiController::class,'getpage']);
Route::get('/get/slidebar',[ApiController::class,'getslidebar']);
Route::get('get/gallery_images',[ApiController::class,'getGalleryimages']);
Route::get('get/teammembers',[ApiController::class,'getteam']);
Route::get('get/team/{id}',[ApiController::class,'getteammembers']);
Route::get('rooms/category/{id}', [ApiController::class, 'getresourcedata']);
Route::get('youtube/vedios', [ApiController::class, 'getyoutubedata']);
Route::get('get/contactDetails',[ApiController::class,'getcontactpage']);
Route::get('/get/Activity/list/{id}',[ApiController::class,'getactivitylist']);
Route::get('/get/menus',[ApiController::class,'getmenus']);
Route::get('/get/testimonial/{id}',[ApiController::class,'gettestimonialdata']);
Route::get('/get/homepagee/sections',[HomeController::class,'gethomepagedetails']);