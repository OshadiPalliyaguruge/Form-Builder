<?php

use App\Http\Controllers\FormController;
use App\Http\Controllers\FormSubmissionController;
use Illuminate\Support\Facades\Route;

// Manual API routes registration
Route::prefix('api')->group(function () {
    // Form routes
    Route::get('forms', [FormController::class, 'index']);
    Route::post('forms', [FormController::class, 'store']);
    Route::get('forms/{form}', [FormController::class, 'show']);
    Route::put('forms/{form}', [FormController::class, 'update']);
    Route::delete('forms/{form}', [FormController::class, 'destroy']);
    
    // Submission routes
    Route::post('forms/{form}/submissions', [FormSubmissionController::class, 'store']);
    Route::get('forms/{form}/submissions', [FormSubmissionController::class, 'index']);
    Route::get('submissions/{submission}', [FormSubmissionController::class, 'show']);
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');