<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Class City
 */
class City extends Model
{
    protected $table = 'cities';

    public $timestamps = true;

    protected $fillable = [        
        'name',
    ];

    protected $guarded = [];
}
