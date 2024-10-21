<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function queue()
    {
        return $this->hasOne(Queue::class);
    }
}
