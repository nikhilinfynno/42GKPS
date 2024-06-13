<?php

namespace App\Services;

use App\Helpers\CommonHelper;
use App\Models\Education;
use App\Models\NativeVillage;
use App\Models\Occupation;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class FilterService
{
     
    public function hofFilters()
    {
        $filters['status'] = User::HOF_STATUS;
        $filters['native_villages'] = NativeVillage::query()->activeStatus()->orderBy('name', 'asc')->get();
        return $filters;
         
    }
    
    protected function commonFilters(){
        $data = [
            'native_villages' => NativeVillage::query()->activeStatus()->orderBy('name', 'asc')->get()->pluck('name', 'id')->toArray(),
            'gender' => config('constant.GENDERS'),
            'marital_statuses' => config('constant.MARITAL_STATUS'),
            'blood_groups' => config('constant.BLOOD_GROUPS_SHORT'),
            'educations' => Education::query()->activeStatus()->orderBy('title', 'asc')->get()->pluck('title', 'id')->toArray(),
            'occupations' => Occupation::query()->activeStatus()->orderBy('name', 'asc')->get()->pluck('name', 'id')->toArray()
        ];
        return $data;
    }

    public function memberFilters($statusFilter = true)
    {
        $filters = $this->commonFilters();
        if($statusFilter){
            $filters['status'] = User::MEMBER_STATUS;
        }
        return $filters;
    }
}
