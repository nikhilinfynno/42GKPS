<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Helpers\CommonHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prefix',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'otp',
        'email_verified_at',
        'password',
        'status',
        'phone',
        'country_code',
        'timezone',
        'work_profile',
        'phone_verified_at',
        'member_code',
        'family_code',
        'avatar',
        'created_by',
        'updated_by',
        'delete_by',
        'parent_id',
        'remember_token',
        'status',
        'user_type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = [
        'full_name', 'phone_number', 'avatar_url','crypt_id','birth_date','member_age','member_gender', 'member_marital_status', 'member_blood_group'
    ];
    
    const ACTIVE = 1 ;
    const INACTIVE = 0 ;
    const DECEASED = 2 ;
    
    const MEMBER_STATUS = [
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Inactive',
        self::DECEASED => 'Deceased'
    ];

    const HOF_STATUS = [
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Inactive',
    ];
    
    const STATUS = [
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Inactive',
        self::DECEASED => 'Deceased',
    ];
    
    const HOF=1;
    const MEMBER=2;
    const IMAGE_PATH = "storage/uploads/users/";

    public function userDetail()
    {
        return $this->hasOne(UserDetail::class);
    }
    
    public function getAvatarUrlAttribute()
    {
        
        if ($this->avatar) {
            return asset(self::IMAGE_PATH . $this->avatar);
        }
        return 'https://via.placeholder.com/100x100.png/3F4196/ffffff?text=' . $this->initials; // Return null or a default image if no image is available
    }
    
    public function getCryptIdAttribute()
    {
        return encrypt($this->id);
    }
    
    public function getFullNameAttribute()
    {
        return $this->prefix . ' ' . $this->middle_name . ' ' . $this->last_name.' ' . $this->first_name;
    }

    public function getPhoneNumberAttribute()
    {
        return (!empty($this->phone)) ? $this->country_code . $this->phone : null;
    }
        
    public function getBirthDateAttribute()
    {
        return (isset($this->userDetail) && !empty($this->userDetail->dob)) ? CommonHelper::getDateByUserTimezone($this->userDetail->dob) : null;
    }

    public function getMemberAgeAttribute()
    {
        $age = null;
        if (isset($this->userDetail) && !empty($this->userDetail->dob)){
            $dob = Carbon::parse($this->userDetail->dob);
            $age = $dob->age;
        }
        return $age;
    }

    public function getMemberGenderAttribute()
    {
        return (isset($this->userDetail) && !empty($this->userDetail->gender)) ? config('constant.GENDERS.'.$this->userDetail->gender) : null;
    }
    
    public function getMemberMaritalStatusAttribute()
    {
        return (isset($this->userDetail) && !empty($this->userDetail->marital_status)) ? config('constant.MARITAL_STATUS.'.$this->userDetail->marital_status) : null;
    }
    
    public function getMemberBloodGroupAttribute()
    {
        return (isset($this->userDetail) && !empty($this->userDetail->blood_group)) ? config('constant.BLOOD_GROUPS.'.$this->userDetail->blood_group) : null;
    }
     
    public function scopeActiveStatus($query)
    {
        return $query->where('status', self::ACTIVE);
    }
    
    public function scopeHof($query)
    {
        return $query->where('user_type', self::HOF);
    }
    
    public function scopeMember($query)
    {
        return $query->where('user_type', self::MEMBER);
    }
    
    public function scopeIsVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function getInitialsAttribute()
    {
        $firstInitial = substr($this->middle_name, 0, 1);
        $lastInitial = substr($this->last_name, 0, 1);
        return $firstInitial . $lastInitial;
    }

    
    
    public function members()
    {
        return $this->hasMany(User::class, 'parent_id');
    }
    
    public function hof()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // common function to filter User data
    public static function applyFilters($query, $request, $fieldMappings)
    {
        foreach ($fieldMappings as $requestField => $dbField) {
            if ($request->has($requestField) && !empty($request->input($requestField))) {
                $searchKeyWord = $request->input($requestField);

                if (is_callable($dbField)) {
                    $dbField($query, $searchKeyWord);
                } else {
                    $query->whereHas('userDetail', function ($query) use ($dbField, $searchKeyWord) {
                        $query->where($dbField, $searchKeyWord);
                    });
                }
            }
        }

        // Text search filter
        if ($request->has('keyword_search') && !empty($request->input('keyword_search'))) {
            $searchTerm = $request->input('keyword_search');
            $query->where(function ($query) use ($searchTerm) {
                $query->where('first_name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('middle_name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                ->orWhere('phone', 'LIKE', "%{$searchTerm}%");
            });
        }
        if ($request->has('min_age') && !empty($request->input('min_age')) && $request->has('max_age') && !empty($request->input('max_age'))) {
            $today = Carbon::today();
            $minAge = $request->input('min_age');
            $maxAge = $request->input('max_age');

            // Calculate the earliest date of birth for the minimum age (i.e., youngest)
            $minDob = $today->copy()->subYears($minAge)->format('Y-m-d');

            // Calculate the latest date of birth for the maximum age (i.e., oldest)
            $maxDob = $today->copy()->subYears($maxAge + 1)->addDay()->format('Y-m-d');

            $query->whereHas('userDetail', function ($query) use ($minDob, $maxDob) {
                $query->where('dob', '<=', $minDob);
                $query->where('dob', '>=', $maxDob);
            });
        }
        
        return $query;
    }
}
