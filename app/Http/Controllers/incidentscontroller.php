<?php

namespace App\Http\Controllers;

use App\Models\doctor;
use App\Models\patient;
use Illuminate\Http\Request;
use App\Models\incidentsmodel;
use Illuminate\Validation\ValidationException;

class incidentscontroller extends Controller
{
    // This is to go to the view with the appropative data
    public function index()
    {
        // Get all the incidents
        $incident=incidentsmodel::get();

        // Turn all the given patient ids en involved persons to there beloning name
        foreach($incident as $ic)
        {
            $personsInvoldeName=[];
            $patientInvoldeName=[];
            foreach(explode(",",$ic->invovled_persons) as $pit)
            {   
                if($pit[0]=="d")
                array_push($personsInvoldeName,doctor::findOrFail(substr($pit,1))->name);
            }

            foreach(explode(",", $ic->patient_id) as $it)
            {
                	array_push($patientInvoldeName, patient::findOrFail($it)->name);
            }
            $ic->invovled_persons=$personsInvoldeName;
            $ic->patient_id=$patientInvoldeName;
        }

        // Goto view with the edited data
        return view("incidents", [
            "incidents" => $incident,
            "doctors" => doctor::get(),
            "patients" => patient::where("dead",0)->get()
        ]);
    }

    // This is to add a new incident in the database
    public function addIncident(Request $req)
    {
        try {
            $req->validate([
                "doctor" => "array|required",
                "doctor.*" => "required|distinct",
                "patient" => "array|required|min:1",
                "patient.*" => "required||distinct|integer",
                "info" => "required|string|min:10|max:255",
                "when" => "required|date_format:Y-m-d\TH:i"
            ], [
                "doctor.required" => "Er moet een doktor geselecteerd zijn",
                "doctor.array" => "De doktor moet met een array meegegeven worden",
                "doctor.*.required" => "De doctor ids is required",
                "patient.required" => "Er moet een patient geselecteerd zijn",
                "patient.array" => "De patient moet met een array meegegeven worden",
                "patient.min" => "Er moet minimaal een patient geselecteerd zijn",
                "patient.*.integer"=>"De array mag alleen maar nummers bevatten",
                "patient.*.required" => "De patient ids is required",
                "info.required" => "De info veld is verplicht in te vullen",
                "info.string" => "De info moet een string zijn",
                "info.min" => "De info moet minimaal 10 charachters lang zijn",
                "info.max" => "De info mag maximaal maar 255 charachters lang zijn",
                "when.required" => "De datum is verplicht om in te vullen",
                "when.date_format" => "De datum moet als volgt zijn geformateerd yyyy-mm-dd\Thh:mm"
            ]);
            

            $incident=new incidentsmodel();
            $incident->patient_id=implode(',',$req->patient);
            $incident->date=$req->when;
            $incident->taken_actions=$req->info;
            $incident->invovled_persons=implode(',',$req->doctor);
            $incident->save();

            return response()->json(["suc"=>"De incident is gemeld"]);
        } catch (ValidationException $ex) {
            return response()->json(["err" => $ex->errors()]);
        }
    }
}
