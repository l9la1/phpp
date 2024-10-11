<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class financials extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'hire_cost',
        'caretaking_costs',
        'payed'
    ];
    public $timestamps = false;

    protected $table = 'financial';

    public function pat()
    {
        return $this->hasMany(patient::class,"id","patient_id");
    }
}
