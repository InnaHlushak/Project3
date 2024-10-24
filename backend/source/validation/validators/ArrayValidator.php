<?php

namespace Palmo\source\validation\validators;

class ArrayValidator extends BaseValidator
{
    public function validate($value): bool
    {
        if(!is_array($value)) {
            $this->errorMessage = "Value must be array";
            return false;
        }

        if(empty($value) ) {
            $this->errorMessage = "Selected is empty";
            return false;
        }

        return true;
    }
}