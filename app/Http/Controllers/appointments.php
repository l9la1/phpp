<?php

namespace App\Http\Controllers;

use App\Models\doctor;
use App\Models\patient;
use App\Models\appointment;
use Illuminate\Http\Request;


class appointments extends Controller
{
    public function getAppointments()
    {
        return view("doctors", ['app' => appointment::get(),"patient"=>patient::get()]);
    }

    public function addapt_Appointment(Request $req) {
        $val=$req->validate([
            'id'=>"required",
            "patname"=>"string|required|max:30",
            "dates"=>"datetime|required",
            "reasons"=>"required|string|max:150"
        ]);
    }
}
