<?php

use App\Http\Controllers\Api\QrAttendanceApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api-key', 'throttle:60,1'])->group(function () {
    Route::post('/qr/lookup', [QrAttendanceApiController::class, 'lookup'])->name('api.qr.lookup');
    Route::post('/attendances/mark', [QrAttendanceApiController::class, 'mark'])->name('api.attendances.mark');
});
