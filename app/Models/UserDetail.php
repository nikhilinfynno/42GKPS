<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $appends = [
        'format_dob'
    ];

    public function getFormatDobAttribute()
    {
        return Carbon::parse($this->attributes['dob'])->format('F j, Y');
    }
    
    public function hofRelation(){
        return $this->belongsTo(HofRelation::class, 'relation_id');
    }
    
    public function nativeVillage(){
        return $this->belongsTo(NativeVillage::class, 'native_village_id');
    }
    
    public function education(){
        return $this->belongsTo(Education::class, 'education_id');
    }
    
    public function occupation(){
        return $this->belongsTo(Occupation::class, 'occupation_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
    
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    
}
