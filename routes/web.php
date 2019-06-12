<?php

Route::get('/', function () {
    return view('welcome');
});
Route::get('/{where}',"AppController@showLogin");
Route::post('/do-authentication',"AppController@authenticate");
Route::get('/centers/management',"AppController@showManagerLogin");
Route::get('/admin/home',"AppController@goToAdminPanel");
Route::get('/admin/mismatch',"AppController@goToMismatches");
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
Route::get('get/shipment-for-management','AppController@getShipmentForManagement');
Route::get('/manager/rectify','AppEngineController@rectByManager');
Route::get('/manager/forward-to-acc','AppController@forwardToAccountants');
Route::get('/get/manager','AppController@getManager');
Route::get('/get/acc','AppController@getAccountant');
Route::get('/acc/get-orders','AppController@getForAcc');
Route::get('/met/expectations','AppEngineController@metExpectations');
Route::get('/didnt-meet/expectations','AppEngineController@didntMeetExpectations');
Route::get('/admin/statistics','AppEngineController@gotoAdminStats');
Route::get('/a/b','AppEngineController@fetchShipmentStats');
Route::get('/admin/document-history','AppController@goToDocumentHistoryPage');
Route::get('/clear-data/{whichBase}','AppController@clearWhichDataBase');
Route::get('/download/records/{which}','AppController@downloadShipmentRecords');
Route::get('/download/complete-shipments','AppController@downloadCompleteShipments');
Route::get('/download/mismatches','AppController@downloadMismatches');
Route::get('/manager/forward-anyway','AppController@forwardToAccountantsAnyway');







Route::get('get/some',function(){
   //$m = new App\Http\Controllers\AppEngineController(); 
   $u = App\ShipmentNotification::where('id',15)->first();
   $m = new App\Http\Controllers\MatchMaker(3); 
   dd($m->stringMismatchDetails());
   //return $m->changeDescToReadableItems($u->kitchenShipment->description);
  //return $m->expectedAmount();
   
});
Route::get('/clear/me',function(){
    Session::forget('center-auth');
    Session::forget('manager-auth');
    Session::forget('cook-auth'); 
    return "DOne";
});