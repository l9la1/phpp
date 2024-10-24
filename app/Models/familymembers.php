<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class familymembers extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'relation',
        'contact_number',
        'patient_id', 
    ];


    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
