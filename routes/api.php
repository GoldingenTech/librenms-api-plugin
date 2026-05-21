<?php

use Illuminate\Support\Facades\Route;

// Explicit middleware: EnforceJson prevents the session-auth redirect (302→/login),
// auth:token authenticates via X-Auth-Token header through ApiTokenGuard.
// We do NOT rely on the named 'api' group because that group's contents can vary
// across LibreNMS versions, and plugin routes loaded via ServiceProvider::register()
// must be self-contained.
Route::group(['middleware' => [
    \App\Http\Middleware\EnforceJson::class,
    'auth:token',
]], function () {
    Route::get('/plugins/get_port_by_mac/{mac_address}', [\blizko\LibrenmsAPIPlugin\Http\Controllers\APIController::class, 'get_device_port_by_mac'])->name('get_device_port_by_mac');
    Route::get('/plugins/get_port_by_deviceid/{device_group_id}', [\blizko\LibrenmsAPIPlugin\Http\Controllers\APIController::class, 'get_device_port_by_device_id'])->name('get_miner_port_by_deviceid');
    Route::get('/plugins/get_device_by_physaddress/{physaddress}', [\blizko\LibrenmsAPIPlugin\Http\Controllers\APIController::class, 'get_device_by_physaddress'])->name('get_device_by_physaddress');
    Route::get('/plugins/get_device_by_physaddress_raw/{physaddress}', [\blizko\LibrenmsAPIPlugin\Http\Controllers\APIController::class, 'get_device_by_physaddress_raw'])->name('get_device_by_physaddress_raw');
    Route::get('/plugins/get_device_sensors/{deviceId}', [\blizko\LibrenmsAPIPlugin\Http\Controllers\APIController::class, 'getDeviceSensors'])->name('get_device_sensors');
});