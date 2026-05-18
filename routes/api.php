<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['api']], function () {
    Route::get('/plugins/get_port_by_mac/{mac_address}', [\blizko\LibrenmsAPIPlugin\Http\Controllers\APIController::class, 'get_device_port_by_mac'])->name('get_device_port_by_mac');
    Route::get('/plugins/get_port_by_deviceid/{device_group_id}', [\blizko\LibrenmsAPIPlugin\Http\Controllers\APIController::class, 'get_device_port_by_device_id'])->name('get_miner_port_by_deviceid');
    Route::get('/plugins/get_device_by_physaddress/{physaddress}', [\blizko\LibrenmsAPIPlugin\Http\Controllers\APIController::class, 'get_device_by_physaddress'])->name('get_device_by_physaddress');
    Route::get('/plugins/get_device_by_physaddress_raw/{physaddress}', [\blizko\LibrenmsAPIPlugin\Http\Controllers\APIController::class, 'get_device_by_physaddress_raw'])->name('get_device_by_physaddress_raw');
});