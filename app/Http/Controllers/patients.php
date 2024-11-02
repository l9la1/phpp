<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Queue;
use App\Models\patient;
use App\Models\roommodel;

use App\Models\financials;
use App\Models\appointment;
use Illuminate\Http\Request;
use App\Models\diedpatientmodel;
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
            $req->validate(
                [
                    "id" => "integer|required",
                    "address" => "required|max:20",
                    "phone" => "required|digits:10|integer",
                    "roomNm" => "required|integer"
                ],
                [
                    "id.required" => "De id veld is verplicht",
                    "id.integer" => "De id moet een nummer zijn",
                    "address.required" => "De adres is een verplicht veld",
                    "address.max" => "De adres mag maar maximaal 20 characters lang zijn",
                    "phone.required" => "De telefoon is een verplicht veld",
                    "phone.digits" => "Het moet een 10 lange nummer zijn",
                    "phone.integer" => "Het telefoonnummer moet een nummer zijn",
                    "roomNm.required" => "De ruimtenummer is verplicht",
                    "roomNm.integer" => "De ruimtenummer moet een nummer zijn"
                ]
            );
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
        $validatedData = $request->validate(
            [
                'name' => 'required|string|max:255',
                'address' => 'required|string',
                'phonenumber' => 'required|string',
                'date_of_birth' => 'required|date',
            ],
            [
                "name.required" => "De naam veld is verplicht",
                "name.string" => "De naam moet een string zijn",
                "name.max" => "De naam mag maximaal 255 zijn",
                "address.required" => "De adres is een verplicht veld",
                "address.max" => "De adres mag maar maximaal 20 characters lang zijn",
                "phonenumber.required"=>"De telefoonnummer is verplicht",
                "phonenumber.string"=>"De telefoonnummer moet een string zijn",
                "date_of_birth.required"=>"De geboortedatum is een verplicht veld",
                "date_of_birth.date"=>"De geboortedatum moet een datum zijn"
            ]
        );

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

    public function kill($id)
    {
        $per=patient::findOrFail($id);
        $per->dead=1;
        $per->save();

        $dp=new diedpatientmodel;
        $dp->patient_id=$id;
        $dp->date=Carbon::now()->toDateString();
        $dp->save();

        return response()->json(["suc"=>"De patient is nu officieel dood"]);
    }
}
