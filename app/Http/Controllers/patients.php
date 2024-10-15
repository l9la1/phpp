<?php

namespace App\Http\Controllers;

use App\Models\financials;
use App\Models\appointment;
use Illuminate\Http\Request;

use App\Models\Queue;
use App\Models\patient;

class patients extends Controller
{
    public function index()
    {
        return view("patients",['financial'=>financials::where("patient_id",1)->limit(5)->get(),'appointments'=>appointment::where("patient_id",1)->orderBy("appointment_date")->get()]);
    }

    public function create()
    {
        return view('patientregister');
    }

    public function store(Request $request)
    {
        // Validate form input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phonenumber' => 'required|string',
            'date_of_birth' => 'required|date',
        ]);

        $validatedData['registration_date'] = now();
    
        // Save patient data
        $patient = patient::create($validatedData);
    
        // Add patient to the queue
        queue::create([
            'patient_id' => 1, // Set patient ID
            'priority' => 1, // Set priority, default is 1
            'status' => 0,   // Status: waiting
        ]);
    
        return redirect()->route('patients.store');
    }
    

    public function thankyou()
    {
        return view('patientregister');
    }
}   
