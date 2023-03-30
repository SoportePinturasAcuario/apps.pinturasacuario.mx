<?php

use Illuminate\Http\Request;

Route::group(['middleware' => ['jwt.auth'], 'prefix' => 'auth'], function () {
    // ONLY SALES AGENTS
        Route::group(['middleware' => ['only_sales_agents']], function(){
            // SalesOrder
                // Exported (Update)
                    Route::post('salesorder/exported', 'Store\SalesOrderController@exported');
                // Status (Update)
                    Route::put('salesorder/{salesOrder}/status', 'Store\SalesOrderController@status_update');
                // Export
                    Route::get('store/salesorder/export', 'Store\SalesOrderController@export');
                // Count
                    Route::get('store/salesorder/last', 'Store\SalesOrderController@last');
            Route::resource('salesorder', 'Store\SalesOrderController');
            // Customer
            Route::resource('store/customer', 'Store\CustomerController');
            // User
            Route::resource('store/user', 'Store\UserController')->only('update');
            // Articles
                Route::resource('store/article', 'Store\ArticleController')->only('store', 'update');
                Route::get('store/article/all/withtrashed', 'Store\ArticleController@all_withtrashed');

            // // Color
            //     Route::resource('color', 'Store\ColorController')->only('index', 'store');
        });

    // PriceList
        Route::resource('pricelist', 'Store\PriceListController');
        // ArticlePriceList
        Route::put('pricelist/{pricelist}/articlepricelist', 'Store\PriceListController@article_price_list_update');
            // ArticlePriceList Customer
            Route::post('pricelist/{pricelist}/customer', 'Store\PriceListController@customer_store');
            Route::delete('pricelist/{pricelist}/customer/{customer}', 'Store\PriceListController@customer_delete');
            // Massive (UPDATE)
            Route::put('pricelist/{pricelist}/articlepricelist/import', 'Store\PriceListController@import');

    // ONLY CUSTOMERS
        // Customers
        Route::group(['middleware' => ['only_customers'], 'prefix' => 'customer'], function(){
            // PurchaceOrder
            Route::resource('purchaceorder', 'Store\PurchaceOrderController');

            // Article
            Route::get('{customer}/article/{article}', 'CustomerController@article_show');
        });

    // Customer Articles
    Route::get('customer/{customer}/article', 'CustomerController@article_index');

    // PurchaceOrder
        Route::post('store/purchaceorder/structurevalidation', 'Store\PurchaceOrderController@structurevalidation');

    // Article
        // Nonexistence
        Route::get('store/article/nonexistence', 'Store\ArticleController@nonexistence');
        // Unusable
        Route::get('store/article/unusable', 'Store\ArticleController@unusable');
    // Articles
    Route::resource('store/article', 'Store\ArticleController')->only('store', 'update');
});

// SalesOder
    // Formats
        Route::get('salesorder/{salesOrder}/format', 'Store\SalesOrderController@format');
// ShippingMethods
    Route::resource('shippingmethod', 'Store\ShippingMethodController')->only('index', 'store');
// Categories
    Route::resource('store/category', 'Store\CategoryController')->only('index', 'store');    
// Articles
    Route::resource('store/article', 'Store\ArticleController')->only('index', 'show');  
    
// TypeOfFinishes
    Route::resource('store/typeoffinishes', 'Store\TypeOfFinishesController')->only('index');
// Color
    Route::get('store/color', 'Store\ColorController@index');
// Product
    Route::resource('store/product', 'Store\ProductController')->only('index');
        // Articles
        Route::get('store/product/{product}/article', 'Store\ProductController@article_index');