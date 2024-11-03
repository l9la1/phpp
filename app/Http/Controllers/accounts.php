<?php

namespace App\Http\Controllers;
use App\Models\doctor;
use App\Models\User;
use Illuminate\Http\Request;

class accounts extends Controller{
    function add_doc(Request $req){
      
        $req->validate([
            "name"=> "string|required",
            "date_of_birth"=> "date|required",
            "contact_email"=> "string|required",
            "contact_phone"=> "integer|required|digits:10",
            "specialty"=> "string|required",
            ]);
            $doc=new doctor;
            $doc->name=$req->name;
            $doc->date_of_birth=$req->date_of_birth;
            $doc->contact_email=$req->contact_email;
            $doc->contact_phone=$req->contact_phone;
            $doc->specialty=$req->specialty;
            $doc->save();
        return redirect()->back()->with("Success","het is gelukt");
    }

    function add_admin(Request $req)
    {
        $req->validate([
            "name"=> "string|required",
            "admin_mail"=> "string|required",
            "password"=> "string|required|min:8|max:25",
        ]);
        // Weet niet hoe ik het moet doen met perms en tokens
        $admin=new User;
        $admin->name=$req->name;
        $admin->admin_mail=$req->email;
        $admin->password=$req->password;
        $admin->save();
        return redirect()->back()->with("Succes","het is gelukt");
    }
   
}