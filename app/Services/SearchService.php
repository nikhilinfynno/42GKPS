<?php

namespace App\Services;

use App\Helpers\CommonHelper;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;


class SearchService
{
    public function getData($request, $fieldMappings,$filters, $genders, $isApi = false)
    {
        $query = User::query()->with(['userDetail.nativeVillage'])
        ->whereDoesntHave('roles', function ($query) {
            $query->where('slug', Role::SUPER_ADMIN);
        })->activeStatus();

        $query = User::applyFilters($query, $request, $fieldMappings);

        $data = $query->select('users.*')->orderBy('id', 'desc');

        // Add pagination for API requests
        if ($isApi) {
            $perPage = $request->input('per_page', config('constant.PAGINATION.PER_PAGE'));
            $perPage = 7;
            $paginatedQuery = $query->paginate($perPage);
            return $this->prepareApiResponse($paginatedQuery);
        } else {
            return $this->prepareDataTableResponse($data,$filters, $genders);
        }
    }
    
    private function prepareDataTableResponse($query, $filters, $genders)
    {
       
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('native_village', function ($data) {
                return $data->userDetail->nativeVillage->name ?? '';
            })
            ->editColumn('marital_status', function ($data) use ($filters) {
                return $filters['marital_statuses'][$data->userDetail->marital_status] ?? '';
            })
            ->editColumn('blood_group', function ($data) use ($filters) {
                return $filters['blood_groups'][$data->userDetail->blood_group] ?? '';
            })
            ->editColumn('gender', function ($data) use ($genders) {
                return $genders[$data->userDetail->gender] ?? '';
            })
            ->editColumn('education', function ($data) {
                return $data->userDetail->education->title ?? '';
            })
            ->editColumn('occupation', function ($data) {
                return $data->userDetail->occupation->name ?? '';
            })
            ->editColumn('email', function ($user) {
                return $user->email ?? '--';
            })
            ->editColumn('phone', function ($user) {
                return $user->phone_number ?? '--';
            })
            ->addColumn('action', function ($user) {
                $btn = '<a href="' . route('hof.edit', ['user' => $user->crypt_id, 'member' => $user->member_code]) . '" class="btn btn-success btn-sm"
                data-bs-toggle="tooltip" 
                data-bs-placement="top" 
                data-bs-original-title="Edit Hof / Family"
                ><i class="bx bx-edit"></i> </a>
                <a href="' . route('hof.show', ['user' => $user->crypt_id]) . '" 
                class="btn btn-info btn-sm"
                data-bs-toggle="tooltip" 
                data-bs-placement="top" 
                data-bs-original-title="Show family details"
                ><i class="ri-eye-line" ></i></a> ';
                return $btn;
            })
            ->editColumn('first_name', function ($user) {
                return $user->full_name;
            })
            ->editColumn('created_at', function ($user) {
                return CommonHelper::getDateByTimezone($user->created_at);
            })
            ->addColumn('native_village', function ($user) {
                return optional($user->userDetail)->native_village_id;
            })
            ->addColumn('gender', function ($user) {
                return optional($user->userDetail)->gender;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    protected function prepareApiResponse($paginatedQuery)
    {
        $data = $paginatedQuery->items();

        return [
            'data' => collect($data)->map(function ($user) {
                return [
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                    'dob' => $user->birth_date,
                    'gender' => $user->member_gender,
                    'age' => $user->member_age,
                    'native_village' => optional($user->userDetail->nativeVillage)->name,
                    'blood_group' => $user->member_blood_group,
                    'education' => optional($user->userDetail->education)->title,
                    'occupation' => optional($user->userDetail->occupation)->name,
                    'phone' => $user->phone_number,
                    'is_hof' => $user->user_type == User::HOF ? true : false,
                    'avatar_url' => $user->avatar_url,
                    'member_code' => $user->member_code,
                    'family_code' => $user->family_code,
                ];
            }),
            'pagination' => [
                'total' => $paginatedQuery->total(),
                'per_page' => $paginatedQuery->perPage(),
                'current_page' => $paginatedQuery->currentPage(),
                'last_page' => $paginatedQuery->lastPage(),
                'from' => $paginatedQuery->firstItem(),
                'to' => $paginatedQuery->lastItem(),
            ],
        ];
    }
    
    public function getMemberDetail($memberCode){
        $user = User::query()->with(['userDetail.nativeVillage'])
        ->whereDoesntHave('roles', function ($query) {
            $query->where('slug', Role::SUPER_ADMIN);
        })->activeStatus()->where('member_code', $memberCode)->first();
        return $this->userData($user);
    }
    
    public function getMemberFamilyDetail($familyCode){
        $users = User::query()->with(['userDetail.nativeVillage'])
        ->whereDoesntHave('roles', function ($query) {
            $query->where('slug', Role::SUPER_ADMIN);
        })->activeStatus()->where('family_code', $familyCode)->get();
        $members = [];
        foreach($users as $user){
            $members[] = $this->userData($user);
        }
        return $members;
    }

    protected function userData($user)
    {
        if(isset($user->id)){
            return [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'dob' => $user->birth_date,
                'gender' => $user->member_gender,
                'age' => $user->member_age,
                'native_village' => optional($user->userDetail->nativeVillage)->name,
                'blood_group' => $user->member_blood_group,
                'education' => optional($user->userDetail->education)->title,
                'education_detail' => $user->userDetail->education_detail,
                'occupation' => optional($user->userDetail->occupation)->name,
                'occupation_detail' => $user->userDetail->occupation_detail,
                'weight' => $user->userDetail->weight,
                'height' => $user->userDetail->height,
                'phone' => $user->phone_number,
                'email' => $user->email,
                'is_hof' => $user->user_type == User::HOF ? true : false,
                'member_code' => $user->member_code,
                'family_code' => $user->family_code,
                'avatar_url' => $user->avatar_url,
                'address' => $user->userDetail->address,
                'state' => optional($user->userDetail->state)->name,
                'country' => optional($user->userDetail->country)->name,
                'city' => optional($user->userDetail->city)->name,
                'relation' =>  optional($user->userDetail->hofRelation)->title ,
            ];
        }
        return [];
    }
    
}
