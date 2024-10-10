<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class appointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'reason',
        'appointment_date'
    ];
    public $timestamps = false;
    protected $table = 'appointments';

    public function doc()
    {
        return $this->belongsTo(doctor::class,"doctor_id","id");
    }

    public function pat()
    {
        return $this->belongsTo(patient::class,"patient_id","id");
    }
}
