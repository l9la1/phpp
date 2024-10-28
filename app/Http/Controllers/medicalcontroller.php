<?php

namespace App\Http\Controllers;

use App\Models\patient;
use Carbon\Carbon;
use App\Models\medicalmodel;
use Illuminate\Http\Request;

class medicalcontroller extends Controller
{
    public function index($id)
    {
        return view("medicalhistory",["patient"=>patient::findOrFail($id),"medicalhistory"=>medicalmodel::where("patient_id",$id)->orderBy("date","desc")->get()]);
    }

    public function addInformation(Request $req)
    {
        $req->validate([
            "id"=>"integer|required",
            "info"=>"required|string|max:255|min:10"
        ],[
            "info.required"=>"De info veld is verplicht",
            "info.string"=>"De info veld moet text zijn",
            "info.min"=>"De info veld moet minimaal 10 karakters zijn",
            "info.max"=>"De info veld mag maximaal 255 lang zijn",
            "id.required"=>"De id veld is verplicht",
            "id.integer"=>"De id moet een nummer zijn"
        ]);

        patient::findOrFail($req->id);
        $inf = new medicalmodel;
        $inf->info=$req->info;
        $inf->patient_id=$req->id;
        $inf->date=Carbon::now()->toDateTimeString();
        $inf->save();
        return redirect()->back()->with(["success"=>"Het is succesvol toegevoegd"]);
    }
}
