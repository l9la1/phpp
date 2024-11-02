<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class incidentsmodel extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'date',
        'involvled_persons',
        'taken_actions',
    ];
    public $timestamps = false;

    protected $table = 'incidents';
    
}
