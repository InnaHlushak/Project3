<?php

namespace Palmo\source\validation\validators;

class RadioValidator extends BaseValidator
{
    public function validate($value): bool
    {
        if(empty($value)) {
            $this->errorMessage = "Is  not specified";
            return false;
        }
        return true;
    }
}