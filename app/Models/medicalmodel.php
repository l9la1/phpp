<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class medicalmodel extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'patient_id',
        'info',
        'date'
    ];
    public $timestamps = false;

    protected $table = 'medicalhistory';

    public function patient()
    {
        return $this->belongsTo(patient::class,"patient_id","id");
    }
}
