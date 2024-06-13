<?php

namespace App\Imports;

use App\Helpers\CommonHelper;
use App\Http\Requests\HofRequest;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\State;
use App\Models\Country;
use App\Models\City;
use App\Models\NativeVillage;
use App\Models\Occupation;
use App\Models\Education;
use App\Models\HofRelation;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class UsersImport implements ToCollection, WithChunkReading, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {
            // Process each row to identify HOF and create users
            $relations = HofRelation::query()->get()->pluck('id','title')->toArray();
            $nativeVillages = NativeVillage::query()->get()->pluck('id','name')->toArray();
            
            $familyCode = '';
          
            foreach ($rows as $row) {
                if(isset($row['first_name']) && !empty($row['first_name'])
                &&
                    isset($row['middle_name']) && !empty($row['middle_name'])
                &&
                    isset($row['last_name']) && !empty($row['last_name'])
                ){
                $this->createOrUpdateUser($row, $relations, $nativeVillages, $familyCode);
                }
            }
        });
    }

    private function createOrUpdateUser($row, $relations, $nativeVillages, &$familyCode)
    {
       
        $genders = array_flip(config('constant.GENDERS'));
        $maritalStatuses = array_flip(config('constant.MARITAL_STATUS'));
        $bloodGroups = array_flip(config('constant.BLOOD_GROUPS_SHORT'));
        $birthDate = (!empty($row['dob'])) ? date('Y-m-d', strtotime($row['dob'])) : null;
        // Create or update HOF details
        $state_id = 12; //default Gujarat
        $country_id = 101; //default India
        $city_id = 783; //default Ahmedabad

        if (isset($row['state']) && !empty($row['state'])) {
            $states = State::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($row['state']) . '%'])->first();
            $state_id = (isset($states->id)) ? $states->id : $state_id;
        }
        if (isset($row['country']) && !empty($row['country'])) {
            $country = Country::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($row['state']) . '%'])->first();
            $country_id = (isset($country->id)) ? $country->id : $country_id;
        }
        if (isset($row['city']) && !empty($row['city'])) {
            $city = City::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($row['state']) . '%'])->first();
            $city_id = (isset($city->id)) ? $city->id : $city_id;
        }

        if (isset($row['relation']) && !empty($row['relation'])) {
            $relation_id = $relations[$row['relation']] ?? null;
        }
        if (isset($row['native_village']) && !empty($row['native_village'])) {
            $native_village_id = $nativeVillages[$row['native_village']] ?? null;
        }

        if (isset($row['occupation']) && !empty($row['occupation'])) {
            $occupation = Occupation::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($row['occupation']) . '%'])->first();
            $occupation_id = (isset($occupation->id)) ? $occupation->id :  Occupation::create(['name' => $row['occupation']])->id;
        }

        if (isset($row['education']) && !empty($row['education'])) {
            $education = Education::whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($row['education']) . '%'])->first();
            $education_id = (isset($education->id)) ? $education->id :  Education::create(['title' => $row['education']])->id;
        }
        // Find HOF in the database using family_code
        if ($row['user_type'] == 'HOF') {
            // If user_type is 1 (HOF), create or update the HOF user
            $hof = User::Create(
                ['email' => $row['email'],
                    'first_name' => $row['first_name'],
                    'middle_name' => $row['middle_name'],
                    'last_name' => $row['last_name'],
                    'email' => $row['email'] ?? null,
                    'user_type' => 1,
                    'country_code' => '+91',
                    'phone' => $row['phone'],
                ]
            );
            $familyCode = CommonHelper::generateCode('family', $hof->id);
            $hof->family_code = $familyCode;
            $hof->member_code = CommonHelper::generateCode('member', $hof->id);
            $hof->avatar = CommonHelper::generateCode('member', $hof->id).'.jpg';
            $hof->save();

                UserDetail::updateOrCreate(
                ['user_id' => $hof->id],
                [
                    'address' => $row['address'],
                    'state_id' => $state_id,
                    'country_id' => $country_id,
                    'city_id' => $city_id,
                    'relation_id' => $relation_id,
                    'native_village_id' => $native_village_id ?? null,
                    'occupation_id' => $occupation_id ?? null,
                    'occupation_detail' => $row['occupation_detail'],
                    'education_id' => $education_id ?? null,
                    'education_detail' => $row['education_detail'],
                    'dob' => $row['dob'] ? $birthDate : null,
                    'weight' => $row['weight'],
                    'height' => $row['height'],
                    'gender' => $genders[$row['gender']] ?? null,
                    'blood_group' => $bloodGroups[$row['blood_group']] ?? null,
                    'marital_status' => $maritalStatuses[$row['marital_status']],
                ]
            );
        } else {
            // For members, find the HOF by family_code and set the parent_id
            $hof = User::where('family_code', $familyCode)->where('user_type', 1)->first();

            if ($hof) {
                $member = User::create([
                    'prefix' => $row['prefix'],
                    'first_name' => $row['first_name'],
                    'middle_name' => $row['middle_name'],
                    'last_name' => $row['last_name'],
                    'email' => $row['email'],
                    'user_type' => 2,
                    'country_code' => '+91',
                    'phone' => $row['phone'],
                    'family_code' => $hof->family_code,
                    'parent_id' => $hof->id,
                ]);
                $member_code = CommonHelper::generateCode('member', $member->id);
                $member->member_code = $member_code;
                $member->avatar = $member_code . '.jpg';
                $member->save();

                // Create member details
                UserDetail::updateOrCreate(
                    ['user_id' => $member->id],
                    [
                    'address' => $row['address'],
                    'state_id' => $state_id,
                    'country_id' => $country_id,
                    'city_id' => $city_id,
                    'relation_id' => $relation_id,
                    'native_village_id' => $native_village_id ?? null,
                    'occupation_id' => $occupation_id ?? null,
                    'occupation_detail' => $row['occupation_detail'],
                    'education_id' => $education_id ?? null,
                    'education_detail' => $row['education_detail'],
                    'dob' => $row['dob'] ? $birthDate : null,
                    'weight' => $row['weight'],
                    'height' => $row['height'],
                    'gender' => $genders[$row['gender']] ?? null,
                    'blood_group' => $bloodGroups[$row['blood_group']] ?? null,
                    'marital_status' => $maritalStatuses[$row['marital_status']]
                ]);
            }
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
