<?php

use App\Http\Controllers\patients;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\appointments;
use App\Http\Controllers\queuecontroler;
use App\Http\Controllers\roomcontroller;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\familycontroller;
use App\Http\Controllers\financcontroller;

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

Route::prefix("administrator")->group(function(){
Route::get("/{what}",[queuecontroler::class,"showQueue"]);
Route::post("/addInvoice",[financcontroller::class,"addInvoices"]);
Route::post("/addRoom",[roomcontroller::class,"addRoom"]);
});

Route::prefix("patient")->group(function(){
    Route::get("/",[patients::class,"index"]);
});
// This is where the user will redirect to if url not found or api
Route::fallback(function () {
    return Redirect::to("/doctor");
});

Route::get('/patientregister', [patients::class, 'create'])->name('patients.create');
Route::post('/patientregister', [patients::class, 'store'])->name('patients.store');
Route::get('/thankyou', [patients::class, 'thankyou'])->name('patients.thankyou');