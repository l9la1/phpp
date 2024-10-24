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
            $req->validate(
                [
                    "id" => "integer|required",
                    "bedAmount" => "integer|required|max:2|min:1",
                    "price" => "required|numeric|min:0.01|max:9999999999.99"
                ],
                [
                    "id.integer" => "De id moet een nummer zijn",
                    "id.required" => "De id veld is verplicht",
                    "bedAmount.integer" => "De aantal bedden moeten een nummer zijn",
                    "bedAmount.required" => "De bed aantal is verplicht",
                    "bedAmount.max" => "Max hoeveeheid personen kamer is twee",
                    "bedAmount.min" => "Minimaal 1 persoons kamer",
                    "price.required" => "De prijs is een verplichte veld",
                    "price.numeric" => "Het bedrag moet een nummer zijn",
                    "price.min" => "De prijs moet minimaal 0,01 zijn",
                    "price.max" => "De prijs mag maximaal 9999999999,99"
                ]
            );
            $rm = roommodel::findOrFail($req->id);
            if ($rm->status == "bezet")
                return response()->json(["err" => "Deze kamer is niet te bewerken"]);
            $rm->bed_amount = $req->bedAmount;
            $rm->price = $req->price;
            $rm->save();
            return response()->json(["suc" => "Kamer is succesvol aangepast"]);
        } catch (ValidationException $ex) {
            return response()->json(["err" => $ex->errors()]);
        }
    }

    function addRoom(Request $req)
    {
        $req->validate(
            [
                "price" => "required|numeric|min:0.01|max:9999999999.99",
                "bedamount" => "required|min:1|max:2|integer"
            ],
            [
                "price.numeric" => "Het bedrag moet een nummer zijn",
                "price.min" => "De prijs moet minimaal 0,01 zijn",
                "price.max" => "De prijs mag maximaal 9999999999,99",
                "bedamount.integer" => "De aantal bedden moeten een nummer zijn",
                "bedamount.required" => "De bed aantal is verplicht",
                "bedamount.max" => "Max hoeveeheid personen kamer is twee",
                "bedamount.min" => "Minimaal 1 persoons kamer",
            ]
        );

        $rm = new roommodel;
        $rm->status = "free";
        $rm->price = $req->price;
        $rm->bed_amount = $req->bedamount;
        $rm->save();
        return redirect()->back()->with("success", "De kamer is succesvol toegevoegd");
    }

    function removeRoom($id)
    {
        roommodel::findOrFail($id)->delete();
        return response()->json(["suc" => "De kamer is verwijderd"]);
    }
}
