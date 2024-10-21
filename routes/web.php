<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\appointments;
use Illuminate\Support\Facades\Redirect;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// All the doctor routes
Route::prefix("doctor")->group(function(){
Route::get('/',[appointments::class,'getAppointments']);
Route::post("/",[appointments::class,"addapt_Appointment"]);
});

// This is where the user will redirect to if url not found or api
Route::fallback(function(){
    return Redirect::to("/doctor");
});

Route::get('/patientregister', [patients::class, 'create'])->name('patients.create');
Route::post('/patientregister', [patients::class, 'store'])->name('patients.store');

// Route::get('/thankyou', action: [patients::class, 'thankyou'])->name('patients.thankyou');

Route::get('/login', [patients::class, 'login'])->name('login');
Route::post('/login', [patients::class, 'login'])->name('login');
