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
    // Initialise the right page
    public function showQueue($what)
    {
        if ($what)
            return view("administration", [
                "queue" => quemodel::orderBy("priority", "asc")->orderBy("id")->get(),
                "pat" => patient::where("approval_state", "1")->get(),
                "rooms" => roommodel::where("status", "free")->get(),
                "app" => appointment::where("appointment_date", ">=", Carbon::now())->orderby("appointment_date")->get(),
                "doctor" => doctor::get(),
                "what" => $what
            ]);
    }

    // This is to move a patient from the queue to the normal list -1 is extern
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

            return response()->json(["suc" => "success vol verplaatst"]);
        } catch (Exception $e) {
        }
    }

    // This is to delete a patient out of the queue 
    public function removeOutOfQueue($id)
    {
        quemodel::findOrFail($id)->delete();
        return response()->json(["suc" => "Succesvol verwijderd"]);
    }

    public function updatePriority($id,$priority)
    {
        $qu = quemodel::findOrFail($id);
        $qu->priority=$priority;
        $qu->save();

    }
}
