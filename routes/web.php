<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\admin\EmployeeController;
use App\Http\Controllers\admin\PlaceController;
use App\Http\Controllers\admin\BookController;
use \App\Http\Middleware\BasicAuthMiddleware;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


// ベーシック認証
Route::group(['middleware' => BasicAuthMiddleware::class], function() {
    Route::get('/', function () {
        // return view('test.test');
        return redirect()->action([BookController::class, 'index']);
    });

// admin
    Route::resource('admin/employee', EmployeeController::class);
    Route::resource('admin/places', PlaceController::class);
    Route::resource('admin/books', BookController::class);
// ファイルアップロード
    Route::post('admin/books/uploadThumbnail', [BookController::class, 'uploadThumbnail']);
// ファイル削除
    Route::post('admin/books/clearThumbnail', [BookController::class, 'clearThumbnail']);
// ユーザーCSV
    Route::post('admin/employee/empImportCsv', [EmployeeController::class, 'empImportCsv']);

});
