<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class accounts extends Controller{
    function add_doc(Request $req){
      
        $req->validate([
            "name"=> "string|required",
            "date_of_birth"=> "date|required",
            "contact_email"=> "string|required",
            "contact_phone"=> "integer|required",
            "specialty"=> "string|required",
            "password"=>"required|min:8",
            ]);
            $doc=new doctor;
            $doc->name=$req->name;
            $doc->date_of_birth=$req->date_of_birth;
            $doc->contact_email=$req->contact_email;
            $doc->contact_phone=$req->contact_phone;
            $doc->specialty=$req->specialty;
            $doc->save();

            $us=new User;
            $us->name=$req->name;
            $us->password=Hash::make($req->password);
            $us->email=$req->contact_email;
            $us->perms=0;
            $us->save();
        return redirect()->back()->with("Success","het is gelukt");
    }

    function add_admin(Request $req)
    {
        $req->validate([
            "name"=> "string|required",
            "admin_mail"=> "string|required",
            "password"=> "string|required",
        ]);
        // Weet niet hoe ik het moet doen met perms en tokens
        $admin=new User;
        $admin->name=$req->name;
        $admin->email=$req->admin_mail;
        $admin->password=Hash::make($req->password);
        $admin->perms=1;
        $admin->save();
        return redirect()->back()->with("Succes","het is gelukt");
    }
   
}