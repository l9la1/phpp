<?php

use App\Http\Controllers\patients;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\appointments;
use App\Http\Controllers\queuecontroler;
use App\Http\Controllers\roomcontroller;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\familycontroller;
use App\Http\Controllers\financcontroller;
use App\Http\Controllers\medicalcontroller;
use App\Http\Controllers\incidentscontroller;
use App\Http\Controllers\Users;

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
Route::get('/',[appointments::class,'getAppointments'])->name('docter.index');
Route::post("/",[appointments::class,"addapt_Appointment"]);
});

Route::prefix("administrator")->group(function(){
Route::get("/{what}",[queuecontroler::class,"showQueue"]);
Route::post("/addInvoice",[financcontroller::class,"addInvoices"]);
Route::post("/addRoom",[roomcontroller::class,"addRoom"]);
});
Route::prefix("medical")->group(function(){
    Route::get("/{id?}",[medicalcontroller::class,"index"]);
    Route::post("/addInformation",[medicalcontroller::class,"addInformation"]);
});
Route::prefix("incidents")->group(function(){
    Route::get("/",[incidentscontroller::class,"index"]);
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

// Route::get('/thankyou', action: [patients::class, 'thankyou'])->name('patients.thankyou');

Route::get('/login', [patients::class, 'showLoginForm'])->name('login.create');
Route::post('/login', [Users::class, 'login'])->name('login.store');
