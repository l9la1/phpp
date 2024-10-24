<?php

namespace App\Http\Controllers;

use App\Models\roommodel;
use App\Models\financials;
use App\Models\appointment;
use Illuminate\Http\Request;

use App\Models\Queue;
use App\Models\patient;
use Illuminate\Validation\ValidationException;

class patients extends Controller
{
    public function index()
    {
        return view("patients", [
            'financial' => financials::where("patient_id", 2)->orderBy("id", "desc")->limit(5)->get(),
            'appointments' => appointment::where("patient_id", 1)->orderBy("appointment_date")->get()
        ]);
    }

    // To store the addapted data in the db
    public function changePatient(Request $req)
    {
        try {
            // Validate the request
            $req->validate([
                "id" => "integer|required",
                "address" => "required|max:20",
                "phone" => "required|digits:10|integer",
                "roomNm" => "required|integer"
            ]);
            $pat = patient::findOrFail($req->id);
            $rm = roommodel::find($req->roomNm);
            if ($pat->assigned_room_id != $req->roomNm && $rm != null &&  $rm->status == "bezet")
                return response()->json(["err" => "de kamer is al bezet"]);
            $room = roommodel::find($pat->assigned_room_id);
            if ($room != null) {
                $room->status = "free";
                $room->save();
            }
            $pat->address = $req->address;
            $pat->phonenumber = $req->phone;
            $pat->assigned_room_id = $req->roomNm;
            $pat->save();
            // Check if not extern
            if ($req->roomNm != -1) {
                $rooms = roommodel::find($req->roomNm);
                $rooms->status = "bezet";
                $rooms->save();
            }
            return response()->json(["suc" => "successvol aangepast"]);
        } catch (ValidationException $er) {
            return response()->json(["err" => $er->errors()]);
        }
    }

    // Delete a patient
    public function deletePatient($id)
    {
        patient::findOrFail($id)->delete();
        return response()->json(["suc" => "patient is permanent verwijderd"]);
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
            'patient_id' => $patient->id, // Set patient ID
            'priority' => 0, // Set priority, default is 0
            'status' => 0,   // Status: waiting
        ]);

        return redirect()->route('patients.store');
    }


    public function thankyou()
    {
        return view('patientregister');
    }
}
