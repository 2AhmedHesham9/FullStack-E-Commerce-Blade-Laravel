<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'phone',
        'locality',
        'address',
        'city',
        'state',
        'landmark',
        'zip',
        'country',
        'user_id',
        'isdefault'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
