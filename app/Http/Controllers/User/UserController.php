<?php

namespace App\Http\Controllers\User;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\HofRequest;
use App\Models\Country;
use App\Models\Education;
use App\Models\HofRelation;
use App\Models\NativeVillage;
use App\Models\Occupation;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserRole;
use App\Notifications\EmailNotification;
use App\Services\FilterService;
use App\Services\SearchService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //TBD: if we are going to keep super admin separate user (it will not be any hof or member) then we need to remove from listing.(right now we have taken super admin as separate user)
        $genders = config('constant.GENDERS');
        if ($request->ajax()) {
            
            $query = User::query()->with(['userDetail.nativeVillage'])->whereDoesntHave('roles', function ($query) {
                $query->where('slug', Role::SUPER_ADMIN);
            })->hof();
             
            if (request()->has('columns.4.search.value') && !empty($request->input('columns.4.search.value'))) {
                $searchKeyWord = $request->input('columns.4.search.value');
                if ($searchKeyWord == 0) {
                    $query->where('status',User::INACTIVE);
                }elseif($searchKeyWord == 1){
                    $query->where('status', User::ACTIVE);
                } 
            }
            
            if (request()->has('columns.2.search.value') && !empty($request->input('columns.2.search.value'))) {
                $searchKeyWord = $request->input('columns.2.search.value');
                Log::info($searchKeyWord);
                $query->whereHas('userDetail', function ($query) use($searchKeyWord){
                    $query->where('native_village_id', $searchKeyWord);
                });
            }
            
             
            $data = $query->select('users.*')->orderBy('id', 'desc');
            return DataTables::of($data)
                ->addIndexColumn()
                
                ->editColumn('gender', function ($data)use($genders) {
                    return $genders[$data->userDetail->gender];
                })
                
                ->addColumn('action', function ($user) {

                    $btn = '<a href="' . route('hof.edit', ['user' => $user->crypt_id, 'member' => $user->member_code]) . '" class="btn btn-success btn-sm"
                    data-bs-toggle="tooltip" 
                    data-bs-placement="top" 
                    data-bs-original-title="Edit Hof / Family"
                    ><i class="bx bx-edit"></i> </a>
                    <a href="'. route('hof.show', ['user' => $user->crypt_id]).'" 
                    class="btn btn-info btn-sm"
                    data-bs-toggle="tooltip" 
                    data-bs-placement="top" 
                    data-bs-original-title="Show family details"
                    ><i class="ri-eye-line" ></i></a> ';
                    // if(empty($user->deleted_at)){
                    // $btn .= '<a href="javascript:void(0)" class="btn btn-danger btn-sm delete-record" data-action="'.route('hof.destroy', ['user' => $user]). '"
                    //     data-bs-toggle="tooltip" 
                    //     data-bs-placement="top" 
                    //     data-bs-original-title="Delete user"
                    //     ><i class="bx bx-trash"></i></a>';
                    // }
                    return $btn;
                })
                ->editColumn('first_name', function ($user) {
                    return $user->full_name;
                })
                ->editColumn('phone', function ($user) {
                    return $user->phone_number;
                })
                ->editColumn('created_at', function ($user) {
                    // return $user->created_at->format('Y-m-d H:i:s'); // Format as per your need
                    return CommonHelper::getDateByTimezone($user->created_at);
                })->addColumn('native_village', function ($user) {
                    return optional($user->userDetail->nativeVillage)->name;
                })->addColumn('gender', function ($user) {
                    return optional($user->userDetail)->gender;
                })
               
                ->rawColumns(['action'])
                ->make(true);
        }
        $filterService = resolve(FilterService::class);
        $filters = $filterService->hofFilters();
        return view('users.index', $filters);
    }


    /**
     * Display a listing of the resource.
     */
    public function members(Request $request)
    {
        $genders = config('constant.GENDERS');
        $filterService = resolve(FilterService::class);
        $filters = $filterService->memberFilters();
        if ($request->ajax()) {
            $fieldMappings = [
                'columns.3.search.value' => 'native_village_id',
                'columns.4.search.value' => 'marital_status',
                'columns.5.search.value' => 'blood_group',
                'columns.6.search.value' => 'education_id',
                'columns.7.search.value' => 'occupation_id',
                'columns.8.search.value' => 'gender',
                'columns.9.search.value' => function ($query, $searchKeyWord) {
                    if ($searchKeyWord == 0) {
                        $query->where('status', User::INACTIVE);
                    } elseif ($searchKeyWord == 1) {
                        $query->where('status', User::ACTIVE);
                    }
                }
            ];
            $searchService = resolve(SearchService::class);
           return $searchService->getData($request, $fieldMappings, $filters, $genders);
        }
          return view('users.members', $filters);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function membersBackup(Request $request)
    {
        $genders = config('constant.GENDERS');
        $filterService = resolve(FilterService::class);
        $filters = $filterService->memberFilters();
        if ($request->ajax()) {

            $query = User::query()
            ->with(['userDetail.nativeVillage'])
            ->whereDoesntHave('roles', function ($query) {
                $query->where('slug', Role::SUPER_ADMIN);
            });

            $columnsToCheck = [
                3 => 'native_village_id',
                4 => 'marital_status',
                5 => 'blood_group',
                6 => 'education_id',
                7 => 'occupation_id',
                8 => 'gender',
            ];

            $filterData = [];
            foreach ($columnsToCheck as $columnIndex => $field) {
                if (request()->has("columns.$columnIndex.search.value") && !empty($request->input("columns.$columnIndex.search.value"))) {
                    $searchKeyWord = $request->input("columns.$columnIndex.search.value");
                    // $query->whereHas('userDetail', function ($query) use ($searchKeyWord, $field) {
                    //     $query->where($field, $searchKeyWord);
                    // });
                    $filterData['user_details'][$field] = $searchKeyWord;
                }
            }

            if (request()->has('columns.9.search.value') && !empty($request->input('columns.9.search.value'))) {
                $searchKeyWord = $request->input('columns.9.search.value');
                // $query->where('status', $searchKeyWord == 0 ? User::INACTIVE : User::ACTIVE);
                $filterData['users']['status'] = $searchKeyWord;
            }


            // $data = $query->select('users.*')->orderBy('id', 'desc');
            $searchService = resolve(SearchService::class);
            $data = $searchService->searchMembers();
           
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('native_village', function ($data) {
                    return $data->userDetail->nativeVillage->name;
                })
                ->editColumn('marital_status', function ($data)use($filters) {
                    return $filters['marital_statuses'][$data->userDetail->marital_status];
                })
                ->editColumn('blood_group', function ($data)use($filters) {
                    return $filters['blood_groups'][$data->userDetail->blood_group];
                })
                ->editColumn('gender', function ($data) use ($genders) {
                    return $genders[$data->userDetail->gender];
                })
                ->editColumn('education', function ($data) {
                    return $data->userDetail->education->title;
                })
                ->editColumn('occupation', function ($data) {
                    return $data->userDetail->occupation->name;
                })
                ->editColumn('phone', function ($user) {
                    return $user->phone_number;
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
                    // if(empty($user->deleted_at)){
                    // $btn .= '<a href="javascript:void(0)" class="btn btn-danger btn-sm delete-record" data-action="'.route('hof.destroy', ['user' => $user]). '"
                    //     data-bs-toggle="tooltip" 
                    //     data-bs-placement="top" 
                    //     data-bs-original-title="Delete user"
                    //     ><i class="bx bx-trash"></i></a>';
                    // }
                    return $btn;
                })
                ->editColumn('first_name', function ($user) {
                    return $user->full_name;
                })
                ->editColumn('created_at', function ($user) {
                    // return $user->created_at->format('Y-m-d H:i:s'); // Format as per your need
                    return CommonHelper::getDateByTimezone($user->created_at);
                })->addColumn('native_village', function ($user) {
                    return optional($user->userDetail)->native_village_id;
                })->addColumn('gender', function ($user) {
                    return optional($user->userDetail)->gender;
                })

                ->rawColumns(['action'])
                ->make(true);
        }
       
        return view('users.members', $filters);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $dropdownData = $this->getDropdownData();
        return view('users.form', $dropdownData);
    }
    
    private function getDropdownData(){
        $data['prefixes'] = config('constant.PREFIX');
        $data['nativeVillages'] = NativeVillage::query()->activeStatus()->get();
        $data['occupations'] = Occupation::query()->activeStatus()->get();
        $data['educations'] = Education::query()->activeStatus()->get();
        $data['maritalStatuses'] = config('constant.MARITAL_STATUS');
        $data['genders'] = config('constant.GENDERS');
        $data['bloodGroups'] = config('constant.BLOOD_GROUPS');
        $data['phone_codes'] = Country::query()->pluck('phone_code')->all();
        $data['countries'] = Country::query()->get();
        $data['relations'] = HofRelation::query()->activeStatus()->get();
        $data['human_heights'] = CommonHelper::getHumanHeight();
        $data['member_status'] = User::MEMBER_STATUS;
        $data['hof_status'] = User::HOF_STATUS;
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HofRequest $request, $hof_id = null)
    {
        $user_id = '';
        $memberId = '';
      
        DB::transaction(
            function () use ($request, &$user_id, $hof_id, &$memberId) {  // Use & to pass by reference
                
                $authUser = auth()->user();
                $content = [];
                $data = [
                    'prefix' => $request->prefix,
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'country_code' => $request->country_code,
                    'phone' => $request->phone,
                    'user_type' => (!empty($hof_id))  ? 2 : 1,
                    'created_by' => $authUser->id,
                    'updated_by' => $authUser->id,
                    'status' => $request->status 
                ];
                if (!empty($hof_id)) {
                    $hof_id = decrypt($hof_id);
                    $data['parent_id'] = $hof_id;
                }else{
                    $password = CommonHelper::generateRandomString();
                    $data['password'] = Hash::make($password);
                    $content = [
                        'subject' => '42GKPS Registration successful.',
                        'message' => [
                            'Your Password for 42GKPS mobile app is: ' . $password,
                            'Thank you for using our application!'
                        ],
                    ];
                   
                }
                if ($request->file('image') != null) {
                    $file = $request->file('image');
                    $data['avatar'] = $this->uploadUserAvatar($file);
                }
                
                $user = User::create($data);
                if(count($content)){
                    Notification::route('mail', $user->email)->notify(new EmailNotification($content));
                }
                
                
                // if user is member then family code will be same as HOF
                $user->family_code = CommonHelper::generateCode('family', !empty($hof_id) ? $hof_id : $user->id);
                // member code will be unique
                $memberId = CommonHelper::generateCode('member', $user->id);
                $user->member_code = $memberId;
                $user->save();
                if ($user->id) {
                    $currentDate = Carbon::now();
                    $birthDate =(!empty($request->dob)) ? Carbon::createFromFormat('Y-m-d', $request->dob) : null;
                    UserDetail::create([
                        'user_id' => $user->id,
                        'weight' => $request->weight,
                        'height' => $request->height,
                        'gender' => $request->gender,
                        'marital_status' => $request->marital_status,
                        'blood_group' => $request->blood_group,
                        'occupation_id' => $request->occupation_id,
                        'occupation_detail' => $request->occupation_detail,
                        'education_id' => $request->education_id ?? null,
                        'education_detail' => $request->education_detail ?? null,
                        'native_village_id' => $request->native_village_id  ?? null,
                        'address' => $request->address,
                        'country_id' => $request->country_id,
                        'state_id' => $request->state_id,
                        'city_id' => $request->city_id,
                        'dob' => $request->dob ? $birthDate : null,
                        'age' => $request->dob ? $birthDate->diffInYears($currentDate) : null,
                        'relation_id' => $request->relation ?? HofRelation::RELATION_SELF 
                    ]);

                    // Get the member role
                    $role = Role::where('slug', Role::MEMBER)->first();

                    if ($role) {
                        // Assign member role
                        UserRole::updateOrCreate(['user_id' => $user->id], ['role_id' => $role->id]);
                    }
                    $user_id = $user->crypt_id;
                }
        });
        if(!empty($user_id)){
            $created_type = (!empty($hof_id)) ? 'Member' : 'HOF';
            return redirect()->route('hof.edit',['user'=> $hof_id ?? $user_id,'member'=>$memberId])->with(['success'=>1, 'message'=> $created_type.' created successfully!']);
        }else{
            return redirect()->back()->withInput($request->input())->withErrors('something went wrong.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $userId)
    {
        $userId = decrypt($userId);
        $user = User::withTrashed()->findOrFail($userId);
        $searchService = resolve(SearchService::class);
        $familyMembers =  $searchService->getMemberFamilyDetail($user->family_code);         
        return view('users.show',compact('familyMembers'));
    }
    
    /**
     * Display the specified resource.
     */
    public function pdfDownload(Request $request, $userId)
    {
        $userId = decrypt($userId);
        $user = User::withTrashed()->findOrFail($userId);
        $searchService = resolve(SearchService::class);
        $data =  $searchService->getMemberDetail($user->member_code);
        // return view('users.pdf', compact('data'));
        $pdf = Pdf::loadView('users.pdf', compact('data'))->setPaper('a4', 'portrait');
        return $pdf->download($user->member_code.'.pdf');
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($user)
    {
        $user = User::query()->with(['userDetail.hofRelation', 'members.userDetail.hofRelation'])->where('id',decrypt($user))->first();
        if(isset($user->id)){
            $dropdownData = $this->getDropdownData();
            $dropdownData['user'] = $user;
            return view('users.form',  $dropdownData);
        }else{
            return redirect()->route('hof.index')->withErrors('something went wrong.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HofRequest $request, $user)
    {
        $user_id = '';
         
        $user = User::query()->where('id', decrypt($user))->first();
        if(isset($user->id)){
            DB::transaction(
                function () use ($request, &$user_id, $user) {  // Use & to pass by reference

                    $authUser = auth()->user();
                    $update = [
                        'prefix' => $request->prefix,
                        'first_name' => $request->first_name,
                        'middle_name' => $request->middle_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'country_code' => $request->country_code,
                        'phone' => $request->phone,
                        'updated_by' => $authUser->id,
                        'status' => $request->status,
                    ];
                    if ($request->file('image') != null) {
                        $file = $request->file('image');
                        $update['avatar'] = $this->uploadUserAvatar($file);
                    }
                    User::where('id', $user->id)->update($update);
                    UserDetail::where('user_id', $user->id)->update([
                        'weight' => $request->weight,
                        'height' => $request->height,
                        'gender' => $request->gender,
                        'marital_status' => $request->marital_status,
                        'blood_group' => $request->blood_group,
                        'occupation_id' => $request->occupation_id,
                        'occupation_detail' => $request->occupation_detail,
                        'education_id' => $request->education_id ?? null,
                        'education_detail' => $request->education_detail ?? null,
                        'native_village_id' => $request->native_village_id  ?? null,
                        'address' => $request->address,
                        'country_id' => $request->country_id,
                        'state_id' => $request->state_id,
                        'city_id' => $request->city_id,
                        'dob' => $request->dob ? Carbon::createFromFormat('Y-m-d', $request->dob) : null,
                        'relation_id' => $request->relation ?? HofRelation::RELATION_SELF 
                    ]);
                    $user_id = $user->crypt_id;
                }
            );
        }
        if (!empty($user_id)) {
            if($user->user_type == User::HOF){
                $userType = 'HOF ';
            }else{
                $userType = 'Member ';
                $user_id = $user->hof->crypt_id;
            }
            
            return redirect()->route('hof.edit', ['user' => $user_id,'member'=>$user->member_code])->with(['success' => 1, 'message' =>  $userType.'updated successfully!']);
        }  
        return redirect()->back()->withInput($request->input())->withErrors('something went wrong.');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user)
    {
        // cases TBD
        // should not delete super admin and admin role users it self
        // if user per admin delete and admin user we change role to member (or ask delete as admin user or permanent delete)
        // if user is HOF then it should not delete directly need to change as member 
        // we should keep super admin seprate user it will not hof or any member
        $authUser = auth()->user();
        $success = false;
        $message = '';
        $user = User::where('id', $user)->first();
        if ($user && $authUser->id != $user->id) {
            if($user->user_type != User::HOF){
                $user->delete();
                $success = true;
                $message = 'User deleted successfully!';
            }
           $message = 'Cannot delete HOF member. Please assign a new HOF and try again.';
        } else {
            $message  = ($authUser->id != $user->id) ? 'Something went wrong!' : 'Can not delete this user.';
        }       
        return response()->json(['success' => $success, 'message' => $message]);
    }
    
    protected function uploadUserAvatar($newFile, $oldFile = null)
    {
        $currentDate = Carbon::now()->toDateString();
        $destinationPath = User::IMAGE_PATH;
        $imageName  = $currentDate . '-' . uniqid() . '.' . $newFile->getClientOriginalExtension();
        $destinationPathWithFile = $destinationPath . $imageName;
        
        if (!empty($oldFile)) {
            //delete old post image
            if (Storage::disk('public')->exists($destinationPath . $oldFile)) {
                Storage::disk('public')->delete($destinationPath . $oldFile);
            }
        }
        try{
            Storage::disk('public')->putFileAs($destinationPath,  $newFile, $imageName);
            return $imageName;
        } catch (Exception $e) {
            return '';
            Log::error($e->getMessage());
        }
    }
}
