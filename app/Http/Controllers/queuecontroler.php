<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\doctor;
use App\Models\patient;
use App\Models\quemodel;
use App\Models\roommodel;
use App\Models\appointment;

class queuecontroler extends Controller
{
    public function showQueue($what)
    {
        if ($what)
            return view("administration", [
                "queue" => quemodel::get(),
                "pat" => patient::get(),
                "rooms" => roommodel::where("status", "free")->get(),
                "app" => appointment::where("appointment_date",">=",Carbon::now())->orderby("appointment_date")->get(),
                "doctor" => doctor::get(),
                "what" => $what
            ]);
    }

    public function addPatientAndAssignRoom($room_num, $pat_id)
    {
        try {
            if ($room_num == -1) {
                $pat = patient::find($pat_id);
                $pat->assigned_room_id = $room_num;
                $pat->approval_state = 1;
                $pat->save();
            } else {
                if (roommodel::where("roomnumber", $room_num)->where("status", "free")->count() == 1 && patient::where("id", $pat_id)->count() == 1) {
                    $pat = patient::find($pat_id);
                    $pat->assigned_room_id = $room_num;
                    $pat->approval_state = 1;
                    $pat->save();

                    $room = roommodel::where("roomnumber", $room_num)->get();
                    $room->status = "bezet";
                    $room->save();
                }
            }

            quemodel::where("patient_id", $pat_id)->delete();

            return response()->json(["mes" => "success vol verplaatst"]);
        } catch (Exception $e) {
        }
    }

    public function removeOutOfQueue($id)
    {
        try {
            quemodel::findOrFail($id)->delete();
            return response()->json(["mes" => "Succesvol verwijderd"]);
        } catch (Exception $e) {
        }
    }

    public function changeApp($id,$date,$doctor)
    {
        $a = appointment::findOrFail($id);
        if(appointment::where("doctor_id",$doctor)->where("appointment_date",$date)->count()==1)
        return response()->json(["mes"=>"De doktor heeft al een afspraak op ".$date]);
        $a->doctor_id=$id;
        $a->appointment_date=$date;
        $a->doctor_id=$doctor;
        $a->save();
        return response()->json(["mes"=>"succesvol aangepast"]);
    }

    public function deleteApp($id)
    {
        appointment::findOrFail($id)->delete();
        return response()->json(["mes"=>"succesvol aangepast"]);
    }
}
