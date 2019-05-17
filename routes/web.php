<?php

Route::get('/', function () {
    return view('welcome');
});
Route::get('/{where}',"AppController@showLogin");
Route::post('/do-authentication',"AppController@authenticate");
Route::get('/centers/management',"AppController@showManagerLogin");
Route::get('/admin/home',"AppController@goToAdminPanel");
Route::get('/admin/logout',"AdminController@logout");
Route::post('add-center','AdminController@addCenter'); 
Route::post('add-manager','AdminController@addManager');
Route::post('add-acc','AdminController@addAcc');
Route::post('add-admin','AdminController@addAdmin');
Route::post('add-kitchen','AdminController@addKitchen');
Route::post('add-pastry','AdminController@addPastry');
Route::post('add-unit','AdminController@addUnit');
Route::get('admin/remove-item-{id}/{type}','AdminController@removeItem');
Route::get('/cooks/home','AppController@goToCooks');
Route::get('/{where}/logout','AppController@logoutOf');
Route::get('/centers/home','AppController@goToCenterPanel');
Route::get('/centers/manager/home','AppController@goToManagerPanel');
Route::get('/accounting/home','AppController@goToAccPanel');
Route::get('receive-values-from/kitchen','AppEngineController@receiveValuesFromKitchen');
Route::get('receive-values-from/center','AppEngineController@receiveValuesFromCenter');
Route::get('get/centers','AppController@getCenters');
Route::get('get/pastries','AppController@getPastries');
Route::get('get/center/shipments','AppController@getCenterShipments');









Route::get('/clear/me',function(){
    Session::forget('center-auth');
    Session::forget('manager-auth');
    Session::forget('cook-auth');
    return "DOne";
});