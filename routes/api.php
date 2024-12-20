<?php

use Illuminate\Http\Request;
use App\Http\Controllers\patients;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\appointments;
use App\Http\Controllers\queuecontroler;
use App\Http\Controllers\roomcontroller;
use App\Http\Controllers\familycontroller;
use App\Http\Controllers\incidentscontroller;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// All the doctor routes
Route::prefix("doctor")->middleware("checkRol:0")->group(function(){
Route::post("/addApointment",[appointments::class,"addApointment"]);
Route::get("/deleteApp/{id}",[appointments::class,"deleteAppointment"]);
});

Route::prefix("/incident")->middleware("checkLogin")->group(function(){
    Route::post("/addIncident",[incidentscontroller::class,"addIncident"]);
});

Route::prefix("patient")->group(function () {
    Route::get("/setPayed/{id}", [patients::class, "setPayed"]);
});

Route::prefix("administrator")->middleware("checkRol:1")->group(function(){
Route::get("/assign_room/{room_id}/{patient_id}",[queuecontroler::class,"addPatientAndAssignRoom"]);
Route::get("/removeQueue/{id}",[queuecontroler::class,"removeOutOfQueue"]);
Route::post("/changeApp",[appointments::class,"changeApp"]);
Route::get("/deleteApp/{id}",[appointments::class,"deleteApp"]);
Route::get("/setPriority/{id}/{priority}",[queuecontroler::class,"updatePriority"]);
Route::post("/change_patient",[patients::class,"changePatient"]);
Route::get("/delete_patient/{id}",[patients::class,"deletePatient"]);
Route::post("/change_family",[familycontroller::class,"addaptFamily"]);
Route::get("/delete_fam/{id}",[familycontroller::class,"deleteFam"]);
Route::post("/addFamily",[familycontroller::class,"addMember"]);
Route::post("/update_room",[roomcontroller::class,"addaptRoom"]);
Route::get("/remove_room/{id}",[roomcontroller::class,"removeRoom"]);
Route::get("/killPatient/{id}",[patients::class,"kill"]);
});
