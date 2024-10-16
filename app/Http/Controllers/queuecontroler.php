<?php

namespace App\Http\Controllers;

use App\Models\appointment;
use App\Models\patient;
use App\Models\quemodel;
use App\Models\roommodel;
use Exception;
use Illuminate\Http\Request;

class queuecontroler extends Controller
{
    public function showQueue()
    {
        return view("administration", [
            "queue" => quemodel::get(),
            "pat" => patient::get(),
            "rooms" => roommodel::where("status","free")->get(),
            "app" => appointment::get()
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
            quemodel::find($id)->delete();
            return response()->json(["mes" => "Succesvol verwijderd"]);
        } catch (Exception $e) {
        }
    }
}
