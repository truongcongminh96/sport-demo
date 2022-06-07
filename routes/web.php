<?php

use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Frontend\IndexController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\AdminProfileController;

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

Route::middleware('admin:admin')->group(function () {
    Route::get('admin/login', [AdminController::class, 'loginForm']);
    Route::post('admin/login', [AdminController::class, 'store'])->name('admin.login');
});


Route::middleware(['auth:sanctum,admin', config('jetstream.auth_session'), 'verified'
])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.index');
    })->name('dashboard')->middleware('auth:admin');
});

// Admin all routes
Route::get('/admin/logout', [AdminController::class, 'destroy'])->name('admin.logout');
Route::get('/admin/profile', [AdminProfileController::class, 'adminProfile'])->name('admin.profile');
Route::get('/admin/profile/edit', [AdminProfileController::class, 'adminProfileEdit'])->name('admin.profile.edit');
Route::post('/admin/profile/edit', [AdminProfileController::class, 'adminProfileStore'])->name('admin.profile.store');
Route::get('/admin/change/password', [AdminProfileController::class, 'adminChangePassword'])->name('admin.change.password');
Route::post('/admin/change/password', [AdminProfileController::class, 'adminUpdateChangePassword'])->name('update.change.password');

// User all routes
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        $id = \Illuminate\Support\Facades\Auth::user()->id;
        $user = \App\Models\User::find($id);
        return view('dashboard', compact('user'));
    })->name('dashboard');
});

Route::get('/', [IndexController::class, 'index']);
Route::get('/user/logout', [IndexController::class, 'userLogout'])->name('user.logout');
Route::get('/user/profile', [IndexController::class, 'userProfile'])->name('user.profile');
Route::post('/user/profile/store', [IndexController::class, 'userProfileStore'])->name('user.profile.store');
Route::get('/user/change/password', [IndexController::class, 'userChangePassword'])->name('change.password');
Route::post('/user/password/update', [IndexController::class, 'userPasswordUpdate'])->name('user.password.update');

// Admin brands All Routes
Route::prefix('brand')->group(function () {
    Route::get('/view', [BrandController::class, 'brandView'])->name('all.brand');
    Route::post('/store', [BrandController::class, 'brandStore'])->name('brand.store');
    Route::get('/edit/{id}', [BrandController::class, 'brandEdit'])->name('brand.edit');
    Route::post('/update', [BrandController::class, 'brandUpdate'])->name('brand.update');
    Route::get('/delete/{id}', [BrandController::class, 'brandDelete'])->name('brand.delete');
});

// Admin category All Routes
Route::prefix('category')->group(function () {
    Route::get('/view', [CategoryController::class, 'categoryView'])->name('all.category');
    Route::post('/store', [CategoryController::class, 'categoryStore'])->name('category.store');
    Route::get('/edit/{id}', [CategoryController::class, 'categoryEdit'])->name('category.edit');
    Route::post('/update', [CategoryController::class, 'categoryUpdate'])->name('category.update');
    Route::get('/delete/{id}', [CategoryController::class, 'categoryDelete'])->name('category.delete');

    // Admin Sub category all routes
    Route::get('/sub/view', [SubCategoryController::class, 'subCategoryView'])->name('all.subcategory');
    Route::post('/sub/store', [SubCategoryController::class, 'subCategoryStore'])->name('subcategory.store');
    Route::get('/sub/edit/{id}', [SubCategoryController::class, 'subCategoryEdit'])->name('subcategory.edit');
    Route::post('/sub/update', [SubCategoryController::class, 'subCategoryUpdate'])->name('subcategory.update');
    Route::get('/sub/delete/{id}', [SubCategoryController::class, 'subCategoryDelete'])->name('subcategory.delete');

    // Admin Sub->Sub category all routes
    Route::get('/sub/sub/view', [SubCategoryController::class, 'subSubCategoryView'])->name('all.subsubcategory');
    Route::get('/subcategory/ajax/{category_id}', [SubCategoryController::class, 'getSubCategory']);
    Route::get('/sub-subcategory/ajax/{subcategory_id}', [SubCategoryController::class, 'getSubSubCategory']);
    Route::post('/sub/sub/store', [SubCategoryController::class, 'subSubCategoryStore'])->name('subsubcategory.store');
    Route::get('/sub/sub/edit/{id}', [SubCategoryController::class, 'subSubCategoryEdit'])->name('subsubcategory.edit');
    Route::post('/sub/sub/update', [SubCategoryController::class, 'subSubCategoryUpdate'])->name('subsubcategory.update');
    Route::get('/sub/sub/delete/{id}', [SubCategoryController::class, 'subSubCategoryDelete'])->name('subsubcategory.delete');
});

// Admin products All Routes
Route::prefix('product')->group(function () {
    Route::get('/add', [ProductController::class, 'addProduct'])->name('add-product');
    Route::post('/store', [ProductController::class, 'storeProduct'])->name('product-store');
    Route::get('/manage', [ProductController::class, 'manageProduct'])->name('manage-product');
    Route::get('/edit/{id}', [ProductController::class, 'editProduct'])->name('product.edit');
    Route::post('/data/update/', [ProductController::class, 'productDataUpdate'])->name('product-update');
    Route::post('/image/update/', [ProductController::class, 'multiImageDataUpdate'])->name('update-product-image');
    Route::post('/thumbnail/update/', [ProductController::class, 'thumbnailImageDataUpdate'])->name('update-product-thumbnail');
    Route::get('/multiimage/delete/{id}', [ProductController::class, 'multiImageDelete'])->name('product.multiimage.delete');
    Route::get('/inactive/{id}', [ProductController::class, 'productInactive'])->name('product.inactive');
    Route::get('/active/{id}', [ProductController::class, 'productActive'])->name('product.active');
    Route::get('/delete/{id}', [ProductController::class, 'productDelete'])->name('product.delete');
});
