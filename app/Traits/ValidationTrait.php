<?php

namespace App\Traits;

trait ValidationTrait
{
    public function getFailedValidationKeys($validator)
    {
        $keys = array_keys($validator->errors()->toArray());
        return $keys;
    }
}
