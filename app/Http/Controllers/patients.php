<?php

namespace App\Http\Controllers;

use App\Models\patient;
use App\Models\roommodel;
use App\Models\financials;
use App\Models\appointment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class patients extends Controller
{
    public function index()
    {
        return view("patients", ['financial' => financials::where("patient_id", 1)->limit(5)->get(), 'appointments' => appointment::where("patient_id", 1)->orderBy("appointment_date")->get()]);
    }

    public function changePatient(Request $req)
    {
        try {
            $req->validate([
                "id" => "integer|required",
                "address" => "required|max:20",
                "phone" => "required|max:10|min:10",
                "roomNm" => "required|integer"
            ]);

            $pat = patient::findOrFail($req->id);
            $room = roommodel::where("id", $pat->assigned_room_id)->limit(1)->get();
            $room->status="free";
            $room->save();
            $pat->address = $req->address;
            $pat->phonenumber = $req->phone;
            $pat->assigned_room_id = $req->roomNm;
            $pat->save();

            $room = roommodel::where("id", $req->roomNm)->get();
            $room->status = "bezet";
            $room->save();
            return response()->json(["suc" => "successvol aangepast"]);
        } catch (ValidationException $er) {
            return response()->json(["err" => $er->errors()]);
        }
    }

    public function deletePatient($id)
    {
        patient::findOrFail($id)->delete();
        return response()->json(["suc" => "patient is permanent verwijderd"]);
    }
}
