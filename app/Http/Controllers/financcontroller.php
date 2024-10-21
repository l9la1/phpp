<?php

namespace App\Http\Controllers;

use App\Models\financials;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class financcontroller extends Controller
{
    function addInvoices(Request $req)
    {
        $req->validate([
            "patient"=>"required|integer",
            "hiring"=>"required|between:0,999999999999.99",
            "caretaking"=>"required|between:0,999999999999.99",
        ]);

        $fin = new financials();
        $fin->patient_id=$req->patient;
        $fin->hire_cost=$req->hiring;
        $fin->caretaking_costs=$req->caretaking;
        $fin->save();
        return Redirect::back();
    }
}
