<?php

use Illuminate\Support\Facades\Route;

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
//ROOT PATH
Route::get('/', 'Front\FrontController@index')->name('homepage');

Route::get('wishlist', 'Front\ShopController@view_wishlist')->name('shop.view_wishlist');
Route::get('all-products', 'Front\ShopController@index')->name('all-products.index');
Route::get('product-details/{id}', 'Front\ShopController@show')->name('product-details.show');
Route::post('shop/add-wishlist', 'Front\ShopController@add_wishlist')->name('shop.wishlist');

Route::get('seller/profile/{id}', 'Front\FrontController@sellerProfile')->name('seller-profile');


Route::get('/checkProductPrice', 'Front\ShopController@checkProductPrice')->name('shop.checkProductPrice');
Route::post('/product/review/{id}', 'Front\ShopController@addReview')->name('shop.addReview');

Route::get('/search-products', 'Front\ShopController@search')->name('shop.search-products');
Route::get('/blogs', 'Front\BlogController@index')->name('blogs');
Route::get('/blog-details/{id}', 'Front\BlogController@show')->name('blog-details');
Route::get('/terms-conditions', 'Front\FrontController@termCondition')->name('term-condition');


Route::post('/news-letter', 'Front\FrontController@subscribeNewsletter')->name('newsletter');
Route::match(['get', 'post'], '/contact-us', 'Front\ContactUsController@index')->name('contactUs');

//ADMIN LOGIN
Route::get('/admin/login', function () {
    return view('admin.auth.login');
})->middleware('guest');

Route::get('/cart', 'Front\CartController@index')->name('cart.index');
Route::patch('/cart/update/{product}', 'Front\CartController@update')->name('cart.update');
Route::post('cart/store/{product}', 'Front\CartController@store')->name('cart.store');
Route::delete('/cart/{product}', 'Front\CartController@destroy')->name('cart.destroy');

// Route::get('/checkout', 'Front\CheckoutController@index')->name('checkout.index');
Route::get('/checkout', 'Front\CheckoutController@index')->name('checkout.index');
Route::post('/checkout/checkout', 'Front\CheckoutController@checkout')->name('checkout.checkout');
Route::get('/checkout/{order_no}/success', 'Front\CheckoutController@success')->name('checkout.success');
Route::get('/download/{orderNo}/{file}', 'Front\CheckoutController@download')->name('download');


Route::get('/guestCheckout', 'Front\CheckoutController@index')->name('guestCheckout.index');

Route::get('/checkout/getFreghtRate', 'Front\CheckoutController@getFreghtRate')->name('checkout.getFreghtRate');


Route::post('product-claim', 'Front\PaymentController@productClaim')->name('product-claim');

Route::post('stripeCharge', 'Front\PaymentController@stripeCharge')->name('stripeCharge');
Route::post('paypalCharge', 'Front\PaymentController@paypalCharge')->name('paypalCharge');
Route::post('splitItCharge', 'Front\PaymentController@splitItCharge')->name('splitItCharge');
Route::get('/checkout/apply-coupon', 'Front\CouponsController@applyCoupon')->name('checkout.coupon');
Route::match(['get'], '/about-us', 'Front\ContactUsController@aboutUs')->name('aboutUs');

Route::get('getCountries', 'Front\FrontController@getCountries')->name('getCountries');
Route::get('getStates/countryId/{id}', 'Front\FrontController@getStates')->name('getStates');
Route::get('getCities/stateId/{id}', 'Front\FrontController@getCities')->name('getCities');

//Seller Routes Start
Route::middleware(['seller'])->prefix('seller')->group(function () {
    Route::get('dashboard', 'Seller\SellerController@dashboard')->name('dashboard');
    Route::get('/getSubCategories', 'Seller\ProductController@getSubCategories')->name('seller.getSubCategories');

    Route::get('getOrderDetail/{id}', 'Seller\SellerController@getOrderDetail')->name('getOrderDetail');

    //Seller Product
    Route::post('add-product', 'Seller\ProductController@store')->name('add-product');
    Route::get('getProduct/{id}', 'Seller\ProductController@getProduct')->name('getProduct');
    Route::post('edit-product/{id}', 'Seller\ProductController@update')->name('edit-product');
    Route::get('removeProduct/{id}', 'Seller\ProductController@removeProduct')->name('removeProduct');
    Route::get('searchSellerProduct', 'Seller\ProductController@searchSellerProduct')->name('searchSellerProduct');

    Route::get('cloneProduct/{id}', 'Seller\ProductController@cloneProduct')->name('cloneProduct');

    //Account Info Update
    Route::post('updateAccountInformation', 'Seller\SellerController@updateAccountInformation')->name('updateAccountInformation');
    // Route::post('addCustomerAddress', 'Seller\SellerController@addCustomerAddress')->name('addCustomerAddress');
    // Route::post('updateCustomerAddress', 'Seller\SellerController@updateCustomerAddress')->name('updateCustomerAddress');
    Route::get('getAddresses/{id}', 'Seller\SellerController@getAddresses')->name('getAddresses');
    //SellerAddress
    Route::post('addSellerAddress', 'Seller\SellerController@addSellerAddress')->name('addSellerAddress');
    Route::post('updateSellerAddress', 'Seller\SellerController@updateSellerAddress')->name('updateSellerAddress');
    Route::post('deleteAddress/{id}', 'Seller\SellerController@deleteAddress')->name('deleteAddress');

    Route::get('getCountries', 'Seller\SellerController@getCountries')->name('getCountries');
    Route::get('getStates/countryId/{id}', 'Seller\SellerController@getStates')->name('getStates');
    Route::get('getCities/stateId/{id}', 'Seller\SellerController@getCities')->name('getCities');
    Route::post('update_password', 'Seller\SellerController@updatePassword')->name('update_password');

    Route::post('mark-product-special-deal', 'Seller\SellerController@mark-ProductSpecialDeal')->name('mark-product-special-deal');

    Route::get('searchSellerOrders', 'Seller\SellerController@searchSellerOrders')->name('searchSellerOrders');

    Route::get('archiveOrder/{id}', 'Seller\SellerController@archiveOrder')->name('archiveOrder');
    Route::get('sellerDeleteOrder/{id}', 'Seller\SellerController@sellerDeleteOrder')->name('sellerDeleteOrder');
    Route::post('mark-product-special-deal', 'Seller\SellerController@markProductSpecialDeal')->name('mark-product-special-deal');
    Route::post('mark-as-featured', 'Seller\SellerController@markAsFeatured')->name('mark-as-featured');
    Route::get('getOrderDetails/{id}', 'Seller\SellerController@getOrderDetails')->name('getOrderDetails');
    Route::get('editOrderTrackingNo/{id}', 'Seller\SellerController@editOrderTrackingNo')->name('editOrderTrackingNo');
    Route::get('updateOrderTrackingNo', 'Seller\SellerController@updateOrderTrackingNo')->name('updateOrderTrackingNo');



    //ORDER
    Route::get('/changeOrderStatus/{id}', 'Seller\SellerController@changeOrderStatus')->name('seller.changeOrderStatus');
});

//Seller Routes End

//Buyer Routes Start

Route::middleware(['buyer'])->prefix('buyer')->group(function () {
    Route::get('dashboard', 'Buyer\BuyerController@dashboard')->name('dashboard');
    Route::get('getOrderDetail/{id}', 'Buyer\BuyerController@getOrderDetail')->name('getOrderDetail');
    Route::get('markOrderStatus', 'Buyer\BuyerController@markOrderStatus')->name('markOrderStatus');
    //Account Info Update
    Route::post('updateAccountInformation', 'Buyer\BuyerController@updateAccountInformation')->name('updateAccountInformation');
    Route::post('addCustomerAddress', 'Buyer\BuyerController@addCustomerAddress')->name('addCustomerAddress');
    Route::post('updateCustomerAddress', 'Buyer\BuyerController@updateCustomerAddress')->name('updateCustomerAddress');

    Route::get('getAddressDetail/{id}', 'Buyer\BuyerController@getAddressDetail')->name('getAddressDetail');
    Route::get('getCountries', 'Buyer\BuyerController@getCountries')->name('getCountries');
    Route::get('getStates/countryId/{id}', 'Buyer\BuyerController@getStates')->name('getStates');
    Route::get('getCities/stateId/{id}', 'Buyer\BuyerController@getCities')->name('getCities');
    Route::post('update_password', 'Buyer\BuyerController@updatePassword')->name('update_password');
    Route::post('deleteAddress/{id}', 'Buyer\BuyerController@deleteAddress')->name('deleteAddress');

    //Buyer Product Wishlist 
    Route::post('add-wishlist', 'Buyer\BuyerController@add_wishlist')->name('buyer.wishlist');
    Route::get('wishlist', 'Buyer\BuyerController@view_wishlist')->name('buyer.view_wishlist');
    Route::get('searchWishlistProduct', 'Buyer\BuyerController@searchWishlistProduct')->name('searchWishlistProduct');


    Route::get('searchBuyerOrders', 'Buyer\BuyerController@searchBuyerOrders')->name('searchBuyerOrders');
    Route::get('getOrderDetails/{id}', 'Buyer\BuyerController@getOrderDetails')->name('buyer.getOrderDetails');
});

//Buyer Routes End


//User Routes
Route::middleware(['user'])->prefix('user')->group(function () {
    Route::get('dashboard', 'User\UserController@dashboard')->name('dashboard');

    //WishList
    Route::get('getOrderDetail/{id}', 'User\UserController@getOrderDetail')->name('getOrderDetail');
    Route::post('add-wishlist', 'User\UserController@add_wishlist')->name('user.wishlist');
    Route::get('wishlist', 'User\UserController@view_wishlist')->name('user.view_wishlist');

    //Account Info Update
    Route::post('updateAccountInformation', 'User\UserController@updateAccountInformation')->name('updateAccountInformation');
    Route::post('addCustomerAddress', 'User\UserController@addCustomerAddress')->name('addCustomerAddress');
    Route::post('updateCustomerAddress', 'User\UserController@updateCustomerAddress')->name('updateCustomerAddress');
    Route::get('getAddressDetail/{id}', 'User\UserController@getAddressDetail')->name('getAddressDetail');

    Route::get('getCountries', 'User\UserController@getCountries')->name('getCountries');
    Route::get('getStates/countryId/{id}', 'User\UserController@getStates')->name('getStates');
    Route::get('getCities/stateId/{id}', 'User\UserController@getCities')->name('getCities');
});

Route::namespace('Admin')->prefix('/admin')->middleware('admin')->group(function () {
    //Dashboard
    Route::get('dashboard', 'AdminController@dashboard')->name('dashboard');

    //CMS
    Route::get('/cms-first-section', 'CmsController@firstSectionIndex')->name('cms-first-section');
    Route::get('/first-section-edit-form/{id}', 'CmsController@cmsFirstSectionEditForm')->name('first-section-edit-form');
    Route::post('/first-section-update/{id}', 'CmsController@firstSectionUpdate')->name('first-section-update');

    Route::get('/cms-first-section-component', 'CmsController@cmsFirstSectionComponent')->name('cms-first-section-component');
    Route::post('/first-section-component-store', 'CmsController@firstSectionComponentStore')->name('first-section-component-store');
    Route::get('/first-section-component-edit/{id}', 'CmsController@firstSectionComponentEdit')->name('first-section-component-edit');
    Route::post('/first-section-component-update/{id}', 'CmsController@firstSectionComponentUpdate')->name('first-section-component-update');

    //Second
    Route::get('/cms-second-section', 'CmsController@secondSectionIndex')->name('cms-second-section');
    Route::get('/cms-second-section-component', 'CmsController@cmsSecondSectionComponent')->name('cms-second-section-component');
    Route::post('/second-section-component-store', 'CmsController@secondSectionComponentStore')->name('second-section-component-store');
    Route::get('/second-section-component-edit/{id}', 'CmsController@secondSectionComponentEdit')->name('second-section-component-edit');
    Route::post('/second-section-component-update/{id}', 'CmsController@secondSectionComponentUpdate')->name('second-section-component-update');

    //cms third
    Route::get('/cms-third-section', 'CmsController@thirdSectionIndex')->name('cms-third-section');
    Route::get('/cms-third-section-component', 'CmsController@cmsThirdSectionComponent')->name('cms-third-section-component');
    Route::post('/third-section-component-store', 'CmsController@thirdSectionComponentStore')->name('third-section-component-store');
    Route::get('/third-section-component-edit/{id}', 'CmsController@thirdSectionComponentEdit')->name('third-section-component-edit');
    Route::post('/third-section-component-update/{id}', 'CmsController@thirdSectionComponentUpdate')->name('third-section-component-update');

    //cms fourth
    Route::get('/cms-terms-and-conditions-section', 'CmsController@TermsAndConditionsSectionIndex')->name('cms-terms-and-conditions-section');
    Route::get('/create-terms-and-condition-form', 'CmsController@createtermsandconditionform')->name('create-terms-and-condition-form');
    Route::post('/terms-and-conditions-store', 'CmsController@termsandconditionsstore')->name('terms-and-conditions-store');
    Route::get('/terms-and-conditions-edit/{id}', 'CmsController@termsandconditionsedit')->name('terms-and-conditions-edit');
    Route::post('/terms-and-conditions-update/{id}', 'CmsController@termsandconditionsupdate')->name('terms-and-conditions-update');

    //cms fifth
    Route::get('/home-banner-section', 'CmsController@homeBannerSection')->name('home-banner-section');
    Route::post('/home-banner-store', 'CmsController@homeBannerStore')->name('home-banner-store');

    Route::get('/search-banner-section', 'CmsController@searchBannerSection')->name('search-banner-section');
    Route::post('/search-banner-store', 'CmsController@searchBannerStore')->name('search-banner-store');

    //EMAIL SETTING
    Route::get('/email-setting', 'CmsController@emailSetting')->name('email-setting');
    Route::post('/post-email-setting', 'CmsController@postEmailSetting')->name('post-email-setting');


    //category
    Route::get('category', 'Categories@index')->name('category');
    Route::get('category/markCategoryAsTop/{id}', 'Categories@markCategoryAsTop')->name('markCategoryAsTop');
    Route::get('category/markCategoryAsShopNow/{id}', 'Categories@markCategoryAsShopNow')->name('markCategoryAsShopNow');
    Route::get('category/statusDisableEnable/{id}', 'Categories@statusDisableEnable')->name('statusDisableEnable');
    Route::get('category/markDisableEnable/{id}', 'Categories@markDisableEnable')->name('markDisableEnable');
    Route::get('category/toggleCategoryStatuses', 'Categories@toggleCategoryStatuses')->name('toggleCategoryStatuses');

    Route::match(['get', 'post'], '/add-category', 'Categories@addCategory')->name('admin.add-category');
    Route::match(['get', 'post'], '/category-edit/{id}', 'Categories@edit')->name('admin.edit-category');
    Route::get('/category-view/{id}', 'Categories@show')->name('category-view');
    Route::delete('category/destroy/{id}', 'Categories@destroy');


    //setting
    Route::match(['get', 'post'], '/settings', 'SettingController@index')->name('settings');

    //PRODUCT
    Route::get('/getSubCategories', 'ProductController@getSubCategories')->name('getSubCategories');
    Route::get('/getOptionValues', 'ProductController@getOptionValues')->name('getOptionValues');
    Route::get('/checkProductSku', 'ProductController@checkProductSku')->name('checkProductSku');
    Route::get('/checkProductSlug', 'ProductController@checkProductSlug')->name('checkProductSlug');
    Route::get('product/changeProductStatus/{id}', 'ProductController@changeProductStatus')->name('changeProductStatus');
    Route::resource('product', 'ProductController');

    //ORDER
    Route::get('/order', 'OrderController@index')->name('order.index');
    Route::get('/order/{id}', 'OrderController@show')->name('order.show');
    Route::get('/order/deleteOrder/{id}', 'OrderController@deleteOrder')->name('order.deleteOrder');



    Route::get('/order/{id}/edit', 'OrderController@edit')->name('order.edit');
    Route::post('/order-update/{id}', 'OrderController@updateOrderTracking')->name('order.update');
    Route::post('/update-order-address/{id}', 'OrderController@updateOrderAddress')->name('order.updateOrderAddress');
    Route::get('order/changeOrderStatus/{id}', 'OrderController@changeOrderStatus')->name('order.changeOrderStatus');

    //PAYMENTS
    Route::get('payments', 'AdminController@payments')->name('payments.index');


    //REVIEW
    Route::get('/review', 'ReviewController@index')->name('review.index');
    Route::get('/review/{id}', 'ReviewController@show')->name('review.show');
    Route::match(['get', 'post'], '/review/edit/{id}', 'ReviewController@edit')->name('review.edit');
    Route::delete('review/destroy/{id}', 'ReviewController@destroy');


    //Manufacturer //used as Brands
    Route::get('/brands', 'ManufacturerController@index')->name('manufacturer.index');
    Route::match(['get', 'post'], '/brands/create', 'ManufacturerController@create')->name('manufacturer.create');
    Route::get('/brands/{id}', 'ManufacturerController@show')->name('manufacturer.show');
    Route::match(['get', 'post'], '/brands/edit/{id}', 'ManufacturerController@edit')->name('manufacturer.edit');
    Route::post('/brands/changeStatus/{id}', 'ManufacturerController@changeStatus')->name('manufacturer.changeStatus');
    Route::delete('/brands/destroy/{id}', 'ManufacturerController@destroy');


    Route::resource('customers', 'CustomersController');
    Route::delete('/customers/destroy/{id}', 'CustomersController@destroy')->name('customers.destroy');

    //Buyer
    Route::resource('buyers', 'CustomersController');
    Route::get('/buyers/{id}', 'CustomersController@edit')->name('customers.edit');
    Route::post('/buyer-update/{id}', 'CustomersController@update')->name('customers.update');
    Route::delete('/buyers/destroy/{id}', 'CustomersController@destroy')->name('customers.destroy');


    Route::get('/catalog/attribute-groups', 'AttributeGroupController@show')->name('catalog.attributeGroups');
    Route::match(['get', 'post'], '/catalog/add-attribute-group', 'AttributeGroupController@add')->name('catalog.addAttributeGroup');
    Route::match(['get', 'post'], '/catalog/edit-attribute-group/{id}', 'AttributeGroupController@edit')->name('catalog.editAttributeGroup');
    Route::delete('/catalog/destroy-attribute-group/{id}', 'AttributeGroupController@destroy')->name('catalog.destroyAttributeGroup');

    Route::get('/catalog/attributes', 'AttributeController@show')->name('catalog.attributes');
    Route::match(['get', 'post'], '/catalog/add-attribute', 'AttributeController@add')->name('catalog.addAttributes');
    Route::match(['get', 'post'], '/catalog/edit-attribute/{id}', 'AttributeController@edit')->name('catalog.editAttribute');
    Route::delete('/catalog/destroy-attribute/{id}', 'AttributeController@destroy')->name('catalog.destroyAttribute');

    Route::get('/catalog/options', 'OptionsController@show')->name('catalog.options');
    Route::match(['get', 'post'], '/catalog/add-option', 'OptionsController@add')->name('catalog.addOption');
    Route::match(['get', 'post'], '/catalog/edit-option/{id}', 'OptionsController@edit')->name('catalog.editOption');
    Route::delete('/catalog/destroy-option/{id}', 'OptionsController@destroy')->name('catalog.destroyOption');

    Route::get('/catalog/option-values', 'OptionValuesController@show')->name('catalog.optionValues');
    Route::match(['get', 'post'], '/catalog/add-option-value', 'OptionValuesController@add')->name('catalog.addOptionValue');
    Route::match(['get', 'post'], '/catalog/edit-option-value/{id}', 'OptionValuesController@edit')->name('catalog.editOptionValue');
    Route::delete('/catalog/destroy-option-value/{id}', 'OptionValuesController@destroy')->name('catalog.destroyOptionValue');

    //Seller
    Route::resource('sellers', 'SellersController');
    Route::post('/seller-update/{id}', 'SellersController@update')->name('sellers.update');
    Route::delete('/sellers/destroy/{id}', 'SellersController@destroy')->name('sellers.destroy');


    //NewsLetter
    Route::get('/newsletter', 'NewsLetterController@index')->name('newsletter.index');
    Route::post('/newsletter/edit/{id}', 'NewsLetterController@edit')->name('newsletter.edit');
    Route::delete('/newsletter/destroy/{id}', 'NewsLetterController@destroy');
    //    Route::delete('customers/delete/{id}','CustomersController@destroy');

    //Shipping Rate
    Route::get('/shipping', 'ShippingRateController@index')->name('shipping.index');
    Route::match(['get', 'post'], '/shipping/create', 'ShippingRateController@create')->name('shipping.create');
    Route::get('/shipping/{id}', 'ShippingRateController@show')->name('shipping.show');
    Route::match(['get', 'post'], '/shipping/edit/{id}', 'ShippingRateController@edit')->name('shipping.edit');
    Route::post('/shipping/changeStatus/{id}', 'ShippingRateController@changeStatus')->name('shipping.changeStatus');
    Route::delete('/shipping/destroy/{id}', 'ShippingRateController@destroy');

    //Blogs
    Route::get('blog/changeBlogStatus/{id}', 'BlogController@changeBlogStatus')->name('changeBlogStatus');
    Route::resource('blog', 'BlogController');

    Route::get('/coupons', 'CouponController@index')->name('coupons.index');
    Route::match(['get', 'post'], '/coupon/create', 'CouponController@create')->name('coupons.create');
    Route::get('/coupon/{id}', 'CouponController@show')->name('coupons.show');
    Route::match(['get', 'post'], '/coupon/edit/{id}', 'CouponController@edit')->name('coupons.edit');
    Route::post('/coupons/changeStatus/{id}', 'CouponController@changeStatus')->name('coupons.changeStatus');
    Route::delete('/coupons/destroy/{id}', 'CouponController@destroy')->name('coupon.destroy');
    //
    route::get('/changePassword', 'SettingController@changePassword');
    route::post('/updateAdminPassword', 'SettingController@updateAdminPassword');

    route::get('/deals', 'DealsController@index')->name('deals.index');
    route::get('/create-deal', 'DealsController@create')->name('deals.create');
    route::post('/store-deal', 'DealsController@store')->name('deals.store');
    route::get('/deals/{id}/edit', 'DealsController@edit')->name('deals.edit');
    route::post('/store-update/{id}', 'DealsController@update')->name('deals.update');

    route::get('deals/changeDealsProductStatus/{id}', 'DealsController@changeDealsProductStatus')->name('deals.changeDealsProductStatus');
    route::delete('deals/destroy/{id}', 'DealsController@destroy')->name('deals.destroy');

    route::get('/featuredAds', 'FeaturedAdController@index')->name('featuredAds.index');
    route::get('/featuredAdsCreate', 'FeaturedAdController@create')->name('featuredAds.create');
    route::post('/featuredAdsstore', 'FeaturedAdController@store')->name('featuredAds.store');
    route::get('/featuredAdsEdit/{id}', 'FeaturedAdController@edit')->name('featuredAds.edit');
    route::post('/featuredAdsUpdate/{id}', 'FeaturedAdController@update')->name('featuredAds.update');
    route::get('featuredAds/changeFeaturedAdStatus/{id}', 'FeaturedAdController@changeFeaturedAdStatus')->name('deals.changeFeaturedAdStatus');
    route::delete('featuredAds/destroy/{id}', 'FeaturedAdController@destroy')->name('featuredAds.destroy');

    route::get('/featuredPackage', 'FeaturedPackageController@index')->name('featuredPackage.index');
    route::get('/editFeaturedPackage/{id}', 'FeaturedPackageController@editFeaturedPackage')->name('featuredPackage.editFeaturedPackage');
    route::post('/featuredPackage/{id}', 'FeaturedPackageController@updatefeaturedPackage')->name('featuredPackage.update');

    route::get('/specialDealPackage', 'SpecialDealPackageController@index')->name('specialDealPackage.index');
    route::get('/specialDealPackage/{id}', 'SpecialDealPackageController@editFeaturedPackage')->name('specialDealPackage.editDealPackage');
    route::post('/specialDealPackage/{id}', 'SpecialDealPackageController@updatefeaturedPackage')->name('specialDealPackage.update');

    //ADMIN SHOP
    route::get('/admin-shop', 'ShopController@adminShop')->name('admin-shop');
    route::post('/update-admin-shop', 'ShopController@updateAdminShop')->name('update-admin-shop');
    route::post('/update-admin-shop-address', 'ShopController@updateAdminShopAddress')->name('update-admin-shop-address');
    route::get('/admin-shop-address', 'ShopController@adminShopAddress')->name('admin-shop-address');

    route::get('/getStates/countryId/{id}', 'AdminController@getStates');
    route::get('/getCities/stateId/{id}', 'AdminController@getCities');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('order/deleteBulkOrder', 'Admin\OrderController@deleteBulkOrders')->name('order.deleteBulkOrders')->middleware('admin');
Route::get('payment/deleteBulkPayment', 'Admin\AdminController@deleteBulkPayment')->name('payment.deleteBulkPayment')->middleware('admin');
