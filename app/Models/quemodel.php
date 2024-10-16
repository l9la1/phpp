<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class quemodel extends Model
{
    use HasFactory;

    protected $fillable = [
        'priority',
        'status',
        'patient_id',
    ];
    protected $table = 'queue';

    public function pat()
    {
        return $this->belongsTo(patient::class,"patient_id","id");
    }
}
