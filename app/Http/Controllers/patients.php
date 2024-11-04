<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Queue;
use App\Models\patient;
use App\Models\roommodel;

use App\Models\financials;
use App\Models\appointment;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\diedpatientmodel;
use Illuminate\Validation\ValidationException;

class patients extends Controller
{
    public function index()
    {
        $user_id = session('user_id');
        $patient = patient::where('login_id', $user_id)->first();
        


        return view("patients", [
            'financial' => financials::where("patient_id", 2)->orderBy("id", "desc")->limit(5)->get(),
            'appointments' => appointment::where("patient_id", 1)->orderBy("appointment_date")->get()
        ]);
    }

    public function setPayed($id)
    {
        if(is_int((int)$id))
        {
            $fin=financials::find($id);
            $fin->payed=1;
            $fin->save();
            return response()->json(['suc'=>""]);
        }

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
            if ($pat->dead == 0) {
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
            } else
                return response()->json(["err" => "Je kunt de doden niet aanpassen"]);
        } catch (ValidationException $er) {
            return response()->json(["err" => $er->errors()]);
        }
    }

    // Delete a patient
    public function deletePatient($id)
    {
        $pt = patient::findOrFail($id);
        if ($pt->dead == 0) {
            $pt->delete();
            return response()->json(["suc" => "patient is permanent verwijderd"]);
        } else {
            return response()->json(["err" => "Je kunt de doden niet verwijderen"]);
        }
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
                'email' => 'required|email|unique:users,email', // Ensure email is unique in users table
                'date_of_birth' => 'required|date',
                'password' => 'required|string|min:8',

            ],
            [
                "name.required" => "De naam veld is verplicht",
                "name.string" => "De naam moet een string zijn",
                "name.max" => "De naam mag maximaal 255 zijn",
                "address.required" => "De adres is een verplicht veld",
                "address.max" => "De adres mag maar maximaal 20 characters lang zijn",
                "phonenumber.required" => "De telefoonnummer is verplicht",
                "phonenumber.string" => "De telefoonnummer moet een string zijn",
                "email.required" => "Het email veld is verplicht",
                "email.email" => "Het email moet een geldig email zijn",
                "email.unique" => "Het email is al in gebruik",
                "date_of_birth.required" => "De geboortedatum is een verplicht veld",
                "date_of_birth.date" => "De geboortedatum moet een datum zijn",
                "password.required" => "Het wachtwoord is een verplicht veld",
                "password.string" => "Het wachtwoord moet een string zijn",
                "password.min" => "Het wachtwoord moet minimaal 8 characters lang zijn",
                
            ]
        );
    

        // Create user data for login
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']), // Encrypt password
            'perms' => 2, // Set permissions for the user
        ]);
    
        // Prepare patient data and set registration date
        $validatedData['registration_date'] = now();
        $validatedData['login_id'] = $user->id; // Associate patient with the new user
    
        // Create patient entry
        $patient = patient::create([
            'login_id' => $user->id, // Set user ID
            'name' => $validatedData['name'],
            'address'=> $validatedData['address'],
            'phonenumber' => $validatedData['phonenumber'],
            'email' => $validatedData['email'],
            'date_of_birth' => $validatedData['date_of_birth'],
            'registration_date' => $validatedData['registration_date'],
        ]);
    
        // Add patient to the queue
        Queue::create([
            'patient_id' => $patient->id, // Set patient ID
            'priority' => 0, // Default priority is 0
            'status' => 0,   // Status: waiting
        ]);
    
        return redirect()->back()->with('success','');
    }
    


    public function thankyou()
    {
        return view('patientregister');
    }

    public function kill($id)
    {
        $per = patient::findOrFail($id);
        if ($per->dead == 0) {
            $rmNum = $per->assigned_room_id;

            if ($rmNum && $rmNum != -1) {
                $rm = roommodel::findOrFail($rmNum);
                $rm->status = "free";
                $rm->save();
            }
            $per->assigned_room_id = -2;

            $per->dead = 1;
            $per->save();

            $dp = new diedpatientmodel;
            $dp->patient_id = $id;
            $dp->date = Carbon::now()->toDateString();
            $dp->save();

            return response()->json(["suc" => "De patient is nu officieel dood"]);
        } else
            return response()->json(["err" => "Patient is al dood"]);
    }

    // Show login form
    public function showLoginForm() {
        return view('login'); 
    }

    // Process login submission
    public function login(Request $request) {
        // Validate and authenticate the user here
    }

}
