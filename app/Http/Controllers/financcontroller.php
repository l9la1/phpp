<?php

namespace App\Http\Controllers;

use App\Models\patient;
use App\Models\roommodel;
use App\Models\financials;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class financcontroller extends Controller
{
    // This is to add a new invoice for a client
    function addInvoices(Request $req)
    {
        $req->validate(
            [
                "patient" => "required|integer",
                "caretaking" => "required|numeric|min:0.00|max:99999999.99"

            ],
            [
                "patient.required" => "De patient is verplicht",
                "patient.integer" => "De patient moet een nummer zijn",
                "caretaking.required"=>"De ziektekosten zijn verplict",
                "caretaking.numeric" => "Het bedrag moet een nummer zijn",
                "caretaking.min" => "De zorgkosten moet minimaal 0,01 zijn",
                "caretaking.max" => "De zorgkosten mag maximaal 99999999,99"
            ]
        );
        $pt = patient::findOrFail($req->patient);
        if ($pt->assigned_room_id != -1 && $pt->assigned_room_id!=null)
            $hire = roommodel::findOrfail($pt->assigned_room_id)->price;
        else
            $hire = 0;
        $fin = new financials();
        $fin->patient_id = $req->patient;
        $fin->hire_cost = $hire;
        $fin->caretaking_costs = $req->caretaking;
        $fin->save();
        return Redirect::back();
    }
}
