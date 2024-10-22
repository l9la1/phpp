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
                "patid" => "string|required|max:30",
                "date" => "date_format:Y-m-d\TH:i|required",
                "reason" => "required|string|max:150|min:10"
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
       public function changeApp($id, $date, $doctor)
       {
           $a = appointment::findOrFail($id);
           if (appointment::where("doctor_id", $doctor)->where("appointment_date", $date)->count() == 1)
               return response()->json(["err" => "De doktor heeft al een afspraak op " . $date]);
           $a->doctor_id = $id;
           $a->appointment_date = $date;
           $a->doctor_id = $doctor;
           $a->save();
           return response()->json(["suc" => "succesvol aangepast"]);
       }
   
       // This is to delete a appointment
       public function deleteApp($id)
       {
           appointment::findOrFail($id)->delete();
           return response()->json(["suc" => "succesvol verwijderd"]);
       }
}
