<?php

use App\Models\appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\patients;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\appointments;
use App\Http\Controllers\familycontroller;
use App\Http\Controllers\queuecontroler;

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
Route::prefix("doctor")->group(function(){
Route::post("/addApointment",[appointments::class,"addApointment"]);
Route::get("/deleteApp/{id}",[appointments::class,"deleteAppointment"]);
});

Route::prefix("administrator")->group(function(){
Route::get("/assign_room/{room_id}/{patient_id}/{priority}",[queuecontroler::class,"addPatientAndAssignRoom"]);
Route::get("/removeQueue/{id}",[queuecontroler::class,"removeOutOfQueue"]);
Route::get("/changeApp/{id}/{date}/{doctor}",[appointment::class,"changeApp"]);
Route::get("/deleteApp/{id}",[appointment::class,"deleteApp"]);
Route::get("/setPriority/{id}/{priority}",[queuecontroler::class,"updatePriority"]);
Route::post("/change_patient",[patients::class,"changePatient"]);
Route::get("/delete_patient/{id}",[patients::class,"deletePatient"]);
Route::post("/change_family",[familycontroller::class,"addaptFamily"]);
Route::get("/delete_fam/{id}",[familycontroller::class,"deleteFam"]);
});