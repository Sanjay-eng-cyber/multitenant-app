<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    //Companies Controller
    Route::post('/companies', [CompanyController::class, 'index']);
    Route::post('/company/store', [CompanyController::class, 'store']);
    Route::put('/company/update/{id}', [CompanyController::class, 'update']);
    Route::post('/company/delete/{id}', [CompanyController::class, 'delete']);
    Route::post('/company/{id}/switch', [CompanyController::class, 'switch']);

    // ProjectS Controller
    Route::post('/projects', [ProjectController::class, 'index']);
    Route::post('/project/store', [ProjectController::class, 'store']);
    Route::post('/project/update/{id}', [ProjectController::class, 'update']);
    Route::post('/project/delete/{id}', [ProjectController::class, 'delete']);

    //Invoices Controller
    Route::post('/invoices', [InvoiceController::class, 'index']);
    Route::post('/invoice/store', [InvoiceController::class, 'store']);
    Route::post('/invoice/update/{id}', [InvoiceController::class, 'update']);
    Route::post('/invoice/delete/{id}', [InvoiceController::class, 'delete']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
