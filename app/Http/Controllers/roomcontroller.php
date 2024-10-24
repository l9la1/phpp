<?php

namespace App\Http\Controllers;

use App\Models\patient;
use App\Models\roommodel;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class roomcontroller extends Controller
{
    function addaptRoom(Request $req)
    {
        try {
            $req->validate([
                "id" => "integer|required",
                "bedAmount" => "integer|required|max:2|min:1",
                "price" => "required|between:0.01,9999999999.99"
            ]);
            $rm=roommodel::findOrFail($req->id);
            if($rm->status=="bezet")
                return response()->json(["err"=>"Deze kamer is niet te bewerken"]);
            $rm->bed_amount=$req->bedAmount;
            $rm->price=$req->price;
            $rm->save();
            return response()->json(["suc"=>"Kamer is succesvol aangepast"]);
        } catch (ValidationException $ex) {
            return response()->json(["err" => $ex->errors()]);
        }
    }

    function addRoom(Request $req)
    {
        $req->validate([
            "price"=>"required|between:0.01,9999999999.99",
            "bedamount"=>"required|min:1|max:2|integer"
        ]);

        $rm = new roommodel;
        $rm->roomnumber=3;
        $rm->status="free";
        $rm->price=$req->price;
        $rm->bed_amount=$req->bedamount;
        $rm->save();
        return redirect()->back()->with("success","De kamer is succesvol toegevoegd");
    }

    function removeRoom($id)
    {
        roommodel::findOrFail($id)->delete();
        return response()->json(["suc"=>"De kamer is verwijderd"]);
    }
}
