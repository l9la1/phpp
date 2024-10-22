<?php

namespace App\Http\Controllers;

use App\Models\patient;
use Illuminate\Http\Request;
use App\Models\familymembers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class familycontroller extends Controller
{
    public function addaptFamily(Request $req)
    {
        try {
            $req->validate([
                "id" => "required|integer",
                "phone" => "required|max:10"
            ]);

            $fam = familymembers::findOrFail($req->id);
            $fam->contact_number = $req->phone;
            $fam->save();
            return response()->json(["suc" => "de familielid is succesvol aangepast"]);
        } catch (ValidationException $ex) {
            return response()->json(["err" => $ex->getMessage()]);
        }
    }

    function deleteFam($id)
    {
        familymembers::findOrFail($id)->delete();
        return response()->json(["suc" => "familielid is succesvol verwijderd"]);
    }

    function addMember(Request $req)
    {
        $req->validate([
            "ptid" => "required|integer",
            "phone" => "required|max:10",
            "relation" => "required|max:20",
            "name" => "required|max:20"
        ]);

        if (patient::where("id", $req->ptid)->count() < 2) {
            $fam = new familymembers();
            $fam->patient_id=$req->ptid;
            $fam->name=$req->name;
            $fam->relation=$req->relation;
            $fam->contact_number=$req->phone;
            $fam->save();
            return Redirect::back();
        } else
            return Redirect::back()->withErrors("To many family");
    }
}
