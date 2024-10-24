<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class roommodel extends Model
{
    use HasFactory;

    protected $fillable = [
        'roomnumber',
        'status',
        'bed_amount',
        'price'
    ];
    protected $table = 'rooms';
    public $timestamps = false;
    

    public function pat()
    {
        return $this->hasOne(patient::class,"assigned_room_id","roomnumber");
    }
}
