<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicApi extends Model
{
    use HasFactory;

    //Fillables
    protected $fillable = [
        'api',
        'unique_id',
        'description',
        'auth',
        'https',
        'cors',
        'link',
        'category'
    ];
}
