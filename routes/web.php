<?php

use App\Http\Controllers\patients;
use App\Http\Middleware\checkPerms;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\appointments;
use App\Http\Controllers\roomcontroller;
use App\Http\Controllers\familycontroller;
use App\Http\Controllers\financcontroller;
use App\Http\Controllers\queuecontroler;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\accounts;
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
Route::prefix("doctor")->middleware("checkRol:0")->group(function(){
Route::get('/',[appointments::class,'getAppointments'])->name('docter.index');
Route::post("/",[appointments::class,"addapt_Appointment"]);
});

Route::prefix("administrator")->middleware("checkRol:1")->group(function(){
Route::get("/{what}",[queuecontroler::class,"showQueue"])->name("administrator.index");
Route::post("/addInvoice",[financcontroller::class,"addInvoices"]);
Route::post("/addFamily",[familycontroller::class,"addMember"]);

Route::post("/add_doc",action: [accounts::class,"add_doc"]);
Route::post("/add_admin",action: [accounts::class,"add_admin"]);
});

Route::prefix("medical")->middleware("checkLogin")->group(function(){
    Route::get("/{id?}",[medicalcontroller::class,"index"]);
    Route::post("/addInformation",[medicalcontroller::class,"addInformation"]);
});

Route::prefix("incidents")->middleware("checkLogin")->group(function(){
    Route::get("/",[incidentscontroller::class,"index"]);
});
Route::prefix("patient")->middleware("checkRol:2")->group(function(){
    Route::get("/",[patients::class,"index"])->name( 'patient.index');
});
// This is where the user will redirect to if url not found or api
Route::fallback(function () {
    return Redirect::to("/login");
});

Route::get('/patientregister', [patients::class, 'create'])->name('patients.create');
Route::post('/patientregister', [patients::class, 'store'])->name('patients.store');

Route::get('/login', [patients::class, 'showLoginForm'])->name('login.create');
Route::post('/login', [Users::class, 'login'])->name('login.store');

Route::get('/logout', [Users::class, 'logout'])->name('logout');