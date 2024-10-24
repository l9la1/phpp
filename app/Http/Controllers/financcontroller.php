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
        $req->validate([
            "patient"=>"required|integer",
            "caretaking"=>"required|between:0,999999999999.99",
        ]);
        $pt=patient::findOrFail($req->patient);
        if($pt->assigned_room_id!=-1)
            $hire=roommodel::findOrfail($pt->assigned_room_id)->price;
        else
            $hire=0;
        $fin = new financials();
        $fin->patient_id=$req->patient;
        $fin->hire_cost=$hire;
        $fin->caretaking_costs=$req->caretaking;
        $fin->save();
        return Redirect::back();
    }
}
