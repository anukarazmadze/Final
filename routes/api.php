<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PartnerCompanyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/report', [PartnerCompanyController::class, 'index'])->middleware(['auth:sanctum', 'PartnerCompany'])->name('report');

// Route::post('/login', [AuthController::class, 'login']);

// Route::post('/generate-token', [PartnerCompanyController::class, 'generateToken'])->name('partner.generate-token');




// Route::middleware('auth:sanctum')->post('/generate-token', [AuthController::class, 'generateToken']);
