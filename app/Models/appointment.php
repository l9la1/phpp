<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class appointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'Patient_id',
        'doctor_id',
        'Reason',
        'Appointment_date'
    ];
    protected $table = 'appointments';

    public function doc()
    {
        return $this->belongsTo(doctor::class,"doctor_id","doctor_id");
    }

    public function pat()
    {
        return $this->belongsTo(patient::class,"Patient_id","Patient_id");
    }
}
