<?php

namespace App\Http\Controllers;

use App\Models\patient;
use App\Models\appointment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class appointments extends Controller
{
    public function getAppointments()
    {
        $app = new appointment();
        $pat = new patient;
        return view("doctors", ['app' => $app->orderBy("appointment_date")->get(), "patient" => $pat->orderBy("name")->get()]);
    }

    public function addapt_Appointment(Request $req)
    {
        try {
            $val = $req->validate([
                'id' => "required|integer",
                "patid" => "integer|required",
                "date" => "date_format:Y-m-d\TH:i|required",
                "reason" => "required|string|max:150|min:10"
            ],
        [
            "id.required"=>"De id veld is verplicht",
            "id.integer"=>"De id veld moet een nummer zijn",
            "patid.integer"=>"De patient id moet een nummer zijn",
            "patid.required"=>"De patient id is een verplicht veld",
            "date.date_format"=>"De datum moet in deze format zijn yyyy-mm-dd hh:ii",
            "date.required"=>"Datum is verplicht",
            "reason.required"=>"De reden veld is verplicht",
            "reason.string"=>"De reden veld moet een string zijn",
            "reason.max"=>"De reden veld mag niet langer dan 150 characters",
            "reason.min"=>"De reden veld moet minimaal 10 characters lang zijn"
        ]);
            $ct = appointment::where("appointment_date",$req->date)->where("doctor_id",1)->count();
            if($ct>1)
                throw ValidationException::withMessages(['date'=>["The date has already been taken."]]);
            $app = appointment::find($req->id);
            $app->patient_id = $req->patid;
            $app->doctor_id = 1;
            $app->reason = $req->reason;
            $app->appointment_date = $req->date;

            $app->save();
        } catch (ValidationException $ex) {
            return response()->json(['err' => $ex->errors()], $ex->status);
        }
    }

    public function addApointment(Request $req)
    {
        try {
            $val = $req->validate(
                [
                    'patientName' => "integer|required",
                    'date' => "date_format:Y-m-d\TH:i|required|unique:appointments,appointment_date",
                    'reason' => "min:10|max:150|required|string"
                ],
                [
                    "patientName.integer"=>"De patient veld moet een nummer zijn",
                    "patientName.required"=>"De patienten veld is verplicht",
                    "date.date_format"=>"De datum moet zo geformateerd zijn yyyy-mm-dd hh:ii",
                    "date.required"=>"De datum veld is verplicht",
                    "date.unique"=>"De appointment moet unique zijn",
                    "reason.min"=>"De reden veld moet minimaal 10 characters lang zijn",
                    "reason.max"=>"De reden veld moet maximaal 150 characters lang zijn",
                    "reason.required"=>"De reden veld is verplicht",
                    "reason.string"=>"De reden moet een string zijn"
                ]
            );

            $app  = new appointment;
            $app->patient_id = $req->patientName;
            $app->doctor_id = 1;
            $app->reason = $req->reason;
            $app->appointment_date = $req->date;
            $app->save();
        } catch (ValidationException $ex) {
            return response()->json(['err' => $ex->errors()], $ex->status);
        }
    }

    public function deleteApointment($id)
    {
        if(is_int((int)$id))
        {
            $ap = appointment::find((int)$id);
            $ap->delete();
        }else throw ValidationException::withMessages(['id'=>'id is niet een integer']);
    }

       // This is for the administration to addapt the appoint of doctor
       public function changeApp(Request $req)
       {
        try
        {
            $req->validate([
                "id"=>"integer|required",
                "doctor"=>"required|integer",
                "date"=>"date_format:Y-m-d\TH:i|required"
            ],
        [
            "id.required"=>"De id is een verplict veld",
            "id.integer"=>"De id moet een nummer zijn",
            "doctor.required"=>"De doctor veld is verplicht",
            "doctor.integer"=>"De doctor veld moet een nummer zijn",
            "date.date_format"=>"De datum moet aan deze volgorde voldoen yyyy-mm-dd hh:ii",
            "date.required"=>"De datum is een verplicht veld"
        ]);
           $a = appointment::findOrFail($req->id);
           if (appointment::where("doctor_id", $req->doctor)->where("appointment_date", $req->date)->count() == 1)
               return response()->json(["err" => "De doktor heeft al een afspraak op " . $req->date]);
           $a->doctor_id = $req->id;
           $a->appointment_date = $req->date;
           $a->doctor_id = $req->doctor;
           $a->save();
           return response()->json(["suc" => "succesvol aangepast"]);
        }catch(ValidationException $ex)
        {
            return response()->json(["err"=>$ex->errors()]);
        }
       }
   
       // This is to delete a appointment
       public function deleteApp($id)
       {
           appointment::findOrFail($id)->delete();
           return response()->json(["suc" => "succesvol verwijderd"]);
       }
}
