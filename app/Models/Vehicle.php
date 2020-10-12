<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Vehicle
 */
class Vehicle extends Model
{

    protected $table = 'vehicles';

    public $timestamps = true;

    protected $with = [];
    protected $guarded = [];
    protected $appends = [];

    protected $fillable = [
        'vehicle_type_id',
        'third_party_owner_id',
        'third_party_driver_id',
        'plate',
        'color',
        'brand',       
    ];



    public function vehicleType()
    {
        return $this->belongsTo('App\Models\VehicleType');
    }

    
    public function thirdPartyOwner()
    {
        return $this->belongsTo('App\Models\ThirdParty','third_party_owner_id');
    }


    public function thirdPartyDriver()
    {
        return $this->belongsTo('App\Models\ThirdParty','third_party_driver_id');
    }

}
