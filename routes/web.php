<?php

use App\Http\Controllers\patients;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\appointments;
use App\Http\Controllers\familycontroller;
use App\Http\Controllers\queuecontroler;
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

Route::prefix("administrator")->group(function(){
Route::get("/{what}",[queuecontroler::class,"showQueue"]);
Route::post("/addFamily",[familycontroller::class,"addMember"]);
});

Route::prefix("patient")->group(function(){
    Route::get("/",[patients::class,"index"]);
});
// This is where the user will redirect to if url not found or api
Route::fallback(function () {
    return Redirect::to("/doctor");
});
