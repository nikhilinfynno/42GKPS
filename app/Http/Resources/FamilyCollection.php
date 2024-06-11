<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FamilyCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            "id" => $this->id,
            "prefix" => $this->prefix,
            "first_name" => $this->first_name,
            "middle_name" => $this->middle_name,
            "last_name" => $this->last_name,
            "email" => $this->email,
            "email_verified_at" => $this->email_verified_at,
            "status" => $this->status,
            "country_code" => $this->country_code,
            "phone" => $this->phone,
            "member_code" => $this->member_code,
            "family_code" => $this->family_code,
            "created_by" => $this->created_by,
            "updated_by" => $this->updated_by,
            "delete_by" => $this->delete_by,
            "parent_id" => $this->parent_id,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "user_type" => $this->user_type,
            "full_name" => $this->full_name,
            "avatar_url" => $this->avatar_url,
            "crypt_id" =>$this->crypt_id,
            // "address" => $this->,
            // "user_id" => $this->,
            // "state_id" => $this->,
            // "country_id" => $this->,
            // "city_id" => $this->,
            // "relation_id" => $this->,
            // "native_village_id" => $this->,
            // "occupation_id" => $this->,
            // "occupation_detail" => $this->,
            // "education_id" => $this->,
            // "education_detail" => $this->,
            // "dob" => $this->,
            // "weight" => $this->,
            // "height" => $this->,
            // "gender" => $this->,
            // "blood_group" => $this->,
            // "marital_status" => $this->,
        ];
    }
}
