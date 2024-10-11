<?php

namespace App\Http\Controllers;

use App\Models\financials;
use App\Models\appointment;
use Illuminate\Http\Request;

class patients extends Controller
{
    public function index()
    {
        return view("patients",['financial'=>financials::where("patient_id",1)->limit(5)->get(),'appointments'=>appointment::where("patient_id",1)->orderBy("appointment_date")->get()]);
    }
}   
