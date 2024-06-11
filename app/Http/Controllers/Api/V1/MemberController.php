<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Services\FilterService;
use App\Services\SearchService;
use Exception;
use Illuminate\Http\Request;

class MemberController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         
        $genders = config('constant.GENDERS');
        $filterService = resolve(FilterService::class);
        $filters = $filterService->memberFilters();
        $fieldMappings = [
            'native_village_id' => 'native_village_id',
            'marital_status' => 'marital_status',
            'blood_group' => 'blood_group',
            'education_id' => 'education_id',
            'occupation_id' => 'occupation_id',
            'gender' => 'gender',
        ];
        $searchService = resolve(SearchService::class);
        $members = $searchService->getData($request, $fieldMappings, $filters, $genders,true);
        
        return $this->paginatedSuccessResponse(200, (count($members['data'])) ? 'Members fetch successfully' : 'No Members found.', $members['data'], $members['pagination']);
    }

    /**
     * Display a listing of the resource.
     */
    public function memberDetail($memberCode)
    {
        $searchService = resolve(SearchService::class);
        $member =  $searchService->getMemberDetail($memberCode);
        return $this->successResponse(200, (isset($member->id)) ? 'Member fetch successfully' : 'Member not found.', $member);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function familyDetail($familyCode)
    {
        $searchService = resolve(SearchService::class);
        $familyMembers =  $searchService->getMemberFamilyDetail($familyCode);
        return $this->successResponse(200, (count($familyMembers)) ? 'Member fetch successfully' : 'Family not found.', $familyMembers);
    }

    /**
     * Display a listing of the resource.
     */
    public function myFamily(Request $request)
    {
        $user = $request->user(); // Get the authenticated user
        $searchService = resolve(SearchService::class);
        $familyMembers =  $searchService->getMemberFamilyDetail($user->family_code);
        return $this->successResponse(200, (count($familyMembers)) ? 'Member fetch successfully' : 'Family not found.', $familyMembers);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function filters()
    {
        $filterService = resolve(FilterService::class);
        $filters = $filterService->memberFilters(false);
        return $this->successResponse(200, (count($filters)) ? 'Filters fetch successfully' : 'Filters not found.', $filters);
    }
    
}
