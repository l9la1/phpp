<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class diedpatientmodel extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'patient_id',
        'date',
    ];
    protected $table = 'diedpatients';
    public $timestamps = false;


    public function patient()
    {
        return $this->belongsTo(patient::class,"patient_id","id");
    }

}
