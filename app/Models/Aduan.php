<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aduan extends Model
{
    use HasFactory;

    protected $table = 'aduans';

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
    ];

}
