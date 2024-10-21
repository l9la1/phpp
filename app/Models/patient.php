<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'Patient_id',
        'Name',
        'Address',
        'phonenumber',
        'date_of_birth',
        'ApprovalState',
        'AssignedRoomID',
        'registrationDate'
    ];
    protected $table = 'patients';

    // This is to show all appointments belonging to the patient
    public function appoint()
    {
        return $this->hasMany(appointment::class,"Patient_id","Patient_id");
    }

    public function user()
{
    return $this->belongsTo(User::class);
}

}
