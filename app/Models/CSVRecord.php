<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsvRecord extends Model
{
    use HasFactory;

    protected $table = "csv_records";

    //Fillables
    protected $fillable = [
        'user_id',
        'file_name',
        'csv_id',
        'name',
        'email',
        'phone',
        'address',
    ];
}
