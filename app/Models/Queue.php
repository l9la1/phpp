<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    protected $table = 'queue';

    protected $fillable = ['priority', 
    'status', 
    'patient_id'];
    public $timestamps = false;


    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
