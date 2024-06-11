<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    const DEFAULT_SELECTED = 12;
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    
    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
