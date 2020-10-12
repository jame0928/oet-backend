<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ThirdParty
 */
class ThirdParty extends Model
{
    use SoftDeletes;

    protected $table = 'third_parties';

    public $timestamps = true;

    protected $with = [];
    protected $guarded = [];
    protected $appends = ['full_name'];

    protected $fillable = [
        'identification',
        'first_name',
        'last_name',
        'second_name',
        'city_id',
        'phone',
        'address',        
    ];


    public function scopeFilterName($query, $name){

        return $query->where(function($query) use($name){
            $name = trim($name);
            $aWords = str_word_count($name,1);
            $quantity_words = count($aWords);

            if($quantity_words == 1){
                $query->where('first_name', 'like', '%'.$name.'%')
                ->orWhere('second_name', 'like', '%'.$name.'%')
                ->orWhere('last_name', 'like', '%'.$name.'%');                
            }else{
                $query->whereRaw("CONCAT_WS(' ',first_name,second_name,last_name) LIKE ?", ["%{$name}%"])
                ->orWhereRaw("CONCAT_WS(' ',last_name,first_name,second_name) LIKE ?", ["%{$name}%"]);                
            }
        });
            
    }


    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    
    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->second_name.' '.$this->last_name;
    }

}
