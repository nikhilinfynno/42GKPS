<?php

namespace App\Imports;

use App\Helpers\CommonHelper;
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
            foreach ($rows as $row) {
                $this->createOrUpdateUser($row);
            }
        });
    }

    private function createOrUpdateUser($row)
    {
        $familyCode = '';
        // Find HOF in the database using family_code
        if ($row['user_type'] == 1) {
            // If user_type is 1 (HOF), create or update the HOF user
            $hof = User::Create(
                ['email' => $row['email']], // Use email or another unique identifier
                [
                    'prefix' => $row['prefix'],
                    'first_name' => $row['first_name'],
                    'middle_name' => $row['middle_name'],
                    'last_name' => $row['last_name'],
                    'email' => $row['email'],
                    'user_type' => $row['user_type'],
                    'country_code' => $row['country_code'],
                    'phone' => $row['phone'],
                    'avatar' => $row['avatar'],
                    'parent_id' => null,
                ]
            );
            $familyCode = CommonHelper::generateCode('family', $hof->id);
            $hof->family_code = $familyCode;
            $hof->member_code = CommonHelper::generateCode('member', $hof->id);
            $hof->save();

          
            $birthDate = (!empty($row['dob'])) ? Carbon::createFromFormat('Y-m-d', $row['dob']) : null;
            // Create or update HOF details
            UserDetail::updateOrCreate(
                ['user_id' => $hof->id],
                [
                    'address' => $row['address'],
                    'state_id' => $row['state_id'],
                    'country_id' => $row['country_id'],
                    'city_id' => $row['city_id'],
                    'relation_id' => $row['relation_id'],
                    'native_village_id' => $row['native_village_id'],
                    'occupation_id' => $row['occupation_id'],
                    'occupation_detail' => $row['occupation_detail'],
                    'education_id' => $row['education_id'],
                    'education_detail' => $row['education_detail'],
                    'dob' => $row['dob'] ? $birthDate : null,
                    'weight' => $row['weight'],
                    'height' => $row['height'],
                    'gender' => $row['gender'],
                    'blood_group' => $row['blood_group'],
                    'marital_status' => $row['marital_status'],
                ]
            );
        } else {
            // For members, find the HOF by family_code and set the parent_id
            $hof = User::where('family_code', $row['family_code'])->where('user_type', 1)->first();

            if ($hof) {
                $member = User::create([
                    'prefix' => $row['prefix'],
                    'first_name' => $row['first_name'],
                    'middle_name' => $row['middle_name'],
                    'last_name' => $row['last_name'],
                    'email' => $row['email'],
                    'user_type' => $row['user_type'],
                    'country_code' => $row['country_code'],
                    'phone' => $row['phone'],
                    'family_code' => $row['family_code'],
                    'avatar' => $row['avatar'],
                    'parent_id' => $hof->id,
                ]);
                $member->member_code = CommonHelper::generateCode('member', $member->id);
                $member->save();

                // Create member details
                UserDetail::create([
                    'address' => $row['address'],
                    'user_id' => $member->id,
                    'state_id' => $row['state_id'],
                    'country_id' => $row['country_id'],
                    'city_id' => $row['city_id'],
                    'relation_id' => $row['relation_id'],
                    'native_village_id' => $row['native_village_id'],
                    'occupation_id' => $row['occupation_id'],
                    'occupation_detail' => $row['occupation_detail'],
                    'education_id' => $row['education_id'],
                    'education_detail' => $row['education_detail'],
                    'dob' => $row['dob'],
                    'weight' => $row['weight'],
                    'height' => $row['height'],
                    'age' => $row['age'],
                    'gender' => $row['gender'],
                    'blood_group' => $row['blood_group'],
                    'marital_status' => $row['marital_status'],
                ]);
            }
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
