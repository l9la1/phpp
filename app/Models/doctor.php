<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class doctor extends Model
{
   
    use HasFactory;
    protected $fillable = [
        'name',
        'date_of_birth',
        'contact_email',
        'contact_phone',
        'speciality',
    ];
    protected $table = 'doctors';

    // This is so you know wich appointments has one doctor
    public function appoint()
    {
        return $this->hasMany(appointment::class,"doctor_id","id");
    }

    public function user()
{
    return $this->belongsTo(User::class);
}

}
