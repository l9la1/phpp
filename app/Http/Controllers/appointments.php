<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\patient;
use App\Models\appointment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class appointments extends Controller
{
    public function getAppointments()
    {
        $currentTime = Carbon::now()->setTimezone('Europe/Amsterdam');
        return view("doctors", ['app' => appointment::where("appointment_date",">=",$currentTime->toDateTimeString())->orderBy("appointment_date")->get(), "patient" => patient::orderBy("name")->where("approval_state",1)->get()]);
    }

    // This is to addapt an appointment of the doctor
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
            return response()->json(['errors' => $ex->errors()], $ex->status);
        }
    }

    // THis is to add a new appointment
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
            return response()->json(['errors' => $ex->errors()], $ex->status);
        }
    }

    // This is to delete a appointment
    public function deleteApointment($id)
    {
        if(is_int((int)$id))
        {
            $ap = appointment::find((int)$id);
            $ap->delete();
        }else throw ValidationException::withMessages(['id'=>'id is niet een integer']);
    }
}
