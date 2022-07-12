<?php

use App\Http\Controllers\Backend\AdminOrderController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\ShippingAreaController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\LanguageController;
use App\Http\Controllers\User\CartPageController;
use App\Http\Controllers\User\CashController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\WishlistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\AdminProfileController;
use App\Http\Controllers\User\OrderController;

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

Route::middleware(['auth:admin'])->group(function () {
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
});

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

// Admin slider All Routes
Route::prefix('slider')->group(function () {
    Route::get('/view', [SliderController::class, 'sliderView'])->name('manage-slider');
    Route::post('/store', [SliderController::class, 'sliderStore'])->name('slider.store');
    Route::get('/edit/{id}', [SliderController::class, 'sliderEdit'])->name('slider.edit');
    Route::post('/update', [SliderController::class, 'sliderUpdate'])->name('slider.update');
    Route::get('/delete/{id}', [SliderController::class, 'sliderDelete'])->name('slider.delete');
    Route::get('/inactive/{id}', [SliderController::class, 'sliderInactive'])->name('slider.inactive');
    Route::get('/active/{id}', [SliderController::class, 'sliderActive'])->name('slider.active');
});

/////////// Frontend All Routes /////////////
/////////// Multi Language All Routes //////////
Route::get('/english/language', [LanguageController::class, 'english'])->name('english.language');
Route::get('/vietnam/language', [LanguageController::class, 'vietnam'])->name('vietnam.language');

/////////// Product Detail Page Url //////////
Route::get('/product/details/{id}/{slug}', [IndexController::class, 'productDetails']);

////////// Frontend product tags page /////////
Route::get('/product/tag/{tag}', [IndexController::class, 'tagWiseProduct']);

////////// Frontend Sub Category wise data ////////
Route::get('/subcategory/product/{subcategory_id}/{slug}', [IndexController::class, 'subCategoryWiseProduct']);

////////// Frontend Sub->SubCategory wise data /////////
Route::get('/subsubcategory/product/{subsubcategory_id}/{slug}', [IndexController::class, 'subSubCategoryWiseProduct']);

////////// Product View Modal with Ajax /////////////
Route::get('/product/view/modal/{id}', [IndexController::class, 'productViewAjax']);

////////// Add to cart with Ajax /////////////
Route::post('/cart/data/store/{id}', [CartController::class, 'addToCart']);

////////// Get data from mini cart /////////////
Route::get('/product/mini/cart/', [CartController::class, 'addMiniCart']);

////////// Remove mini cart /////////////
Route::get('/minicart/product-remove/{rowId}', [CartController::class, 'removeMiniCart']);

////////// Add to wish list /////////////
Route::post('/add-to-wishlist/{product_id}', [CartController::class, 'addToWishlist']);

Route::group(['prefix' => 'user', 'middleware' => ['user', 'auth'], 'namespace' => 'User'], function () {
    ////////// Frontend wishlist /////////////
    Route::get('/wishlist', [WishlistController::class, 'viewWishlist'])->name('wishlist');
    Route::get('/get-wishlist-product', [WishlistController::class, 'getWishlistProduct']);
    Route::get('/wishlist-remove/{id}', [WishlistController::class, 'removeWishlistProduct']);
    Route::post('/stripe/order', [OrderController::class, 'stripeOrder'])->name('stripe.order');
    Route::post('/cash/order', [CashController::class, 'cashOrder'])->name('cash.order');
    Route::get('/my/orders', [OrderController::class, 'myOrders'])->name('my.orders');
    Route::get('/order_details/{order_id}', [OrderController::class, 'orderDetails']);
    Route::get('/invoice_download/{order_id}', [OrderController::class, 'invoiceDownload']);
    Route::post('/return/order/{order_id}', [OrderController::class, 'returnOrder'])->name('return.order');
    Route::get('/return/order/list', [OrderController::class, 'returnOrderList'])->name('return.order.list');
    Route::get('/cancel/order/list', [OrderController::class, 'cancelOrderList'])->name('cancel.orders');
});

Route::get('/mycart', [CartPageController::class, 'myCart'])->name('mycart');
Route::get('/user/get-cart-product', [CartPageController::class, 'getCartProduct']);
Route::get('/user/cart-remove/{rowId}', [CartPageController::class, 'removeCartProduct']);
Route::get('/cart-increment/{rowId}', [CartPageController::class, 'cartIncrement']);
Route::get('/cart-decrement/{rowId}', [CartPageController::class, 'cartDecrement']);

// Admin coupons All Routes
Route::prefix('coupons')->group(function () {
    Route::get('/view', [CouponController::class, 'couponView'])->name('manage-coupon');
    Route::post('/store', [CouponController::class, 'couponStore'])->name('coupon.store');
    Route::get('/edit/{id}', [CouponController::class, 'couponEdit'])->name('coupon.edit');
    Route::post('/update', [CouponController::class, 'couponUpdate'])->name('coupon.update');
    Route::get('/delete/{id}', [CouponController::class, 'couponDelete'])->name('coupon.delete');
});

// Admin shipping All Routes
Route::prefix('shipping')->group(function () {
    Route::get('/province/view', [ShippingAreaController::class, 'provinceView'])->name('manage-province');
    Route::post('/province/store', [ShippingAreaController::class, 'provinceStore'])->name('province.store');
    Route::get('/province/edit/{id}', [ShippingAreaController::class, 'provinceEdit'])->name('province.edit');
    Route::post('/province/update', [ShippingAreaController::class, 'provinceUpdate'])->name('province.update');
    Route::get('/province/delete/{id}', [ShippingAreaController::class, 'provinceDelete'])->name('province.delete');

    // Manage district
    Route::get('/district/view', [ShippingAreaController::class, 'districtView'])->name('manage-district');
    Route::post('/district/store', [ShippingAreaController::class, 'districtStore'])->name('district.store');
    Route::get('/district/edit/{id}', [ShippingAreaController::class, 'districtEdit'])->name('district.edit');
    Route::post('/district/update', [ShippingAreaController::class, 'districtUpdate'])->name('district.update');
    Route::get('/district/delete/{id}', [ShippingAreaController::class, 'districtDelete'])->name('district.delete');

    // Manage ward
    Route::get('/ward/view', [ShippingAreaController::class, 'wardView'])->name('manage-ward');
    Route::post('/ward/store', [ShippingAreaController::class, 'wardStore'])->name('ward.store');
    Route::get('/ward/edit/{id}', [ShippingAreaController::class, 'wardEdit'])->name('ward.edit');
    Route::post('/ward/update', [ShippingAreaController::class, 'wardUpdate'])->name('ward.update');
    Route::get('/ward/delete/{id}', [ShippingAreaController::class, 'wardDelete'])->name('ward.delete');
    Route::get('/ward/district/{province_id}', [ShippingAreaController::class, 'getDistrict']);
});

Route::post('/coupon-apply', [CartController::class, 'couponApply']);
Route::get('/coupon-calculation', [CartController::class, 'couponCalculation']);
Route::get('/coupon-remove', [CartController::class, 'couponRemove']);

// Checkout
Route::get('/checkout', [CartController::class, 'checkoutCreate'])->name('checkout');
Route::get('/district-get/ajax/{province_id}', [CheckoutController::class, 'getDistrictsAjax']);
Route::get('/ward-get/ajax/{district_id}', [CheckoutController::class, 'getWardsAjax']);
Route::post('/checkout/store', [CheckoutController::class, 'checkoutStore'])->name('checkout.store');

// Admin Orders All Routes
Route::prefix('orders')->group(function () {
    Route::get('/pending/orders', [AdminOrderController::class, 'pendingOrders'])->name('pending-orders');
    Route::get('/pending/order/details/{order_id}', [AdminOrderController::class, 'pendingOrderDetails'])->name('pending.order.details');
    Route::get('/confirmed/orders', [AdminOrderController::class, 'confirmedOrders'])->name('confirmed-orders');
    Route::get('/processing/orders', [AdminOrderController::class, 'processingOrders'])->name('processing-orders');
    Route::get('/picked/orders', [AdminOrderController::class, 'pickedOrders'])->name('picked-orders');
    Route::get('/shipped/orders', [AdminOrderController::class, 'shippedOrders'])->name('shipped-orders');
    Route::get('/delivered/orders', [AdminOrderController::class, 'deliveredOrders'])->name('delivered-orders');
    Route::get('/cancel/orders', [AdminOrderController::class, 'cancelOrders'])->name('cancel-orders');
    Route::get('/pending/confirm/{order_id}', [AdminOrderController::class, 'pendingToConfirm'])->name('pending-confirm');
    Route::get('/confirm/processing/{order_id}', [AdminOrderController::class, 'confirmToProcessing'])->name('confirm.processing');
    Route::get('/processing/picked/{order_id}', [AdminOrderController::class, 'processingToPicked'])->name('processing.picked');
    Route::get('/picked/shipped/{order_id}', [AdminOrderController::class, 'pickedToShipped'])->name('picked.shipped');
    Route::get('/shipped/delivered/{order_id}', [AdminOrderController::class, 'shippedToDelivered'])->name('shipped.delivered');
    Route::get('/invoice/download/{order_id}', [AdminOrderController::class, 'adminInvoiceDownload'])->name('invoice.download');
});

// Admin Report Orders All Routes
Route::prefix('reports')->group(function () {
    Route::get('/view', [ReportController::class, 'reportView'])->name('all-reports');
    Route::post('/search/by/date', [ReportController::class, 'reportByDate'])->name('search-by-date');
    Route::post('/search/by/month', [ReportController::class, 'reportByMonth'])->name('search-by-month');
    Route::post('/search/by/year', [ReportController::class, 'reportByYear'])->name('search-by-year');
});

// Admin Report Users All Routes
Route::prefix('all-users')->group(function () {
    Route::get('/view', [AdminProfileController::class, 'allUsers'])->name('all-users');
});
