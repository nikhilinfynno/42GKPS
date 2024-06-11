<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NativeVillage extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    const ACTIVE = 1;
    const INACTIVE = 0;
    public function scopeActiveStatus($query)
    {
        return $query->where('status', self::ACTIVE);
    }
}
