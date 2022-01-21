<?php


Route::get('/', function () {
    return redirect()->route('l5-swagger.api');
});

Auth::routes(['register' => false]);

Route::get('/mailable', function () {

});
