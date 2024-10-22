<?php

namespace App\Http\Controllers;

use App\Models\patient;
use Illuminate\Http\Request;
use App\Models\familymembers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class familycontroller extends Controller
{
    // To store the addapted data in the db
    public function addaptFamily(Request $req)
    {
        try {
            // Validate the input
            $req->validate([
                "id" => "required|integer",
                "phone" => "required|digits:10|integer"
            ]);

            $fam = familymembers::findOrFail($req->id);
            $fam->contact_number = $req->phone;
            $fam->save();
            return response()->json(["suc" => "de familielid is succesvol aangepast"]);
        } catch (ValidationException $ex) {
            return response()->json(["err" => $ex->errors()]);
        }
    }

    // This is to delete a familymember
    function deleteFam($id)
    {
        familymembers::findOrFail($id)->delete();
        return response()->json(["suc" => "familielid is succesvol verwijderd"]);
    }

    // This is to add a new family member to the db
    function addMember(Request $req)
    {
        try {
            // Validate the data
            $req->validate([
                "ptid" => "required|integer",
                "phone" => "required|digits:10|integer",
                "relation" => "required|max:20",
                "name" => "required|max:20"
            ]);
            // Check if there aren't two family members because you can only have two family members per client
            if (patient::where("id", $req->ptid)->count() < 2) {
                $fam = new familymembers();
                $fam->patient_id = $req->ptid;
                $fam->name = $req->name;
                $fam->relation = $req->relation;
                $fam->contact_number = $req->phone;
                $fam->save();
                return response()->json(["suc" => "Successvol toegevoegd"]);
            } else
                return response()->json(["err" => "Te veel familieleden"]);
        } catch (ValidationException $ex) {
            return response()->json(["err" => $ex->errors()]);
        }
    }
}
