<?php

namespace Palmo\source\validation\validators;

class EmailValidator extends BaseValidator
{
    public function validate($value): bool
    {
        if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errorMessage = "Please enter a valid email address!";
            return false;
        }
        return true;
    }
}