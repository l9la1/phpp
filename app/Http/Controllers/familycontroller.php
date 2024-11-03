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
            ],
        [
            "id.required"=>"De id veld is verplicht",
            "id.integer"=>"De id moet een nummer zijn",
            "phone.required"=>"De telefoon is verplicht",
            "phone.digits"=>"De telefoon moet 10 digits lang zijn",
            "phone.integer"=>"De telefoon moet een nummer zijn"
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
            ],
        [
            "ptid.required"=>"De patient id is verplicht",
            "ptid.integer"=>"De patient id moet een nummer zijn",
            "phone.required"=>"De telefoon is verplicht",
            "phone.digits"=>"De telefoon moet 10 digits lang zijn",
            "phone.integer"=>"De telefoon moet een nummer zijn",
            "relation.required"=>"De relatie is verplicht",
            "relation.max"=>"De relatie mag maximaal 20 characters lang zijn",
            "name.required"=>"De naam is verplicht",
            "name.max"=>"De naam mag maximaal 20 characters zijn"
        ]);
        if(patient::findOrFail($req->ptid)->dead==0)
        {
            // Check if there aren't two family members because you can only have two family members per client
            if (patient::findOrFail($req->ptid)->count() < 2) {
                $fam = new familymembers();
                $fam->patient_id = $req->ptid;
                $fam->name = $req->name;
                $fam->relation = $req->relation;
                $fam->contact_number = $req->phone;
                $fam->save();
                return response()->json(["suc" => "Successvol toegevoegd"]);
            } else
                return response()->json(["err" => "Te veel familieleden"]);
        }else
        return response()->json(["err"=>"Je kunt geen nieuwe familieleden toevoegen aan een patient die dood is"]);
        } catch (ValidationException $ex) {
            return response()->json(["err" => $ex->errors()]);
        }
    }
}
