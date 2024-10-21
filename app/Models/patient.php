<?php

namespace App\Models;

use App\Models\financials;
use App\Models\familymembers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',  // should match the actual field name in the table
        'address',  // should match the actual field name in the table
        'phonenumber',
        'date_of_birth',
        'approval_state',
        'assigned_room_id',
        'registration_date'
    ];
    public $timestamps = false;
    protected $table = 'patients';

    // This is to show all appointments belonging to the patient
    public function appoint()
    {
        return $this->hasMany(appointment::class,"patient_id","id");
    }

    public function fin()
    {
        return $this->belongsToMany(financials::class,"id","patient_id");
    }

    public function familyMembers()
    {
        return $this->hasMany(familymembers::class);
    }

    public function que()
    {
        return $this->hasOne(quemodel::class,"patient_id","id");
    }

    public function room()
    {
        return $this->hasOne(roommodel::class,"roomnumber","assigned_room_id");
    }

    public function queue()
    {
        return $this->hasOne(Queue::class);
    }
}
