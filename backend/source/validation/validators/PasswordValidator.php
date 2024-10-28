<?php

namespace Palmo\source\validation\validators;

class PasswordValidator extends BaseValidator
{

private $minLength;
    public function __construct($minLengt) 
    {
        $this->minLength = $minLengt;
    }
    public function validate($value): bool
    {
        if(!is_string($value) || empty(trim($value))) {
            $this->errorMessage = "Please enter a password!";
            return false;
        }

        $length = strlen($value);
        if($length < $this->minLength) {
            $this->errorMessage = "The password must contain at least " . $this->minLength . " characters";
            return false;
        }

        if(!(preg_match('/[A-Z]/', $value)) || !(preg_match('/\d/', $value))) {
            $this->errorMessage = "The password must contain at least one capital letter and at least one number";
            return false;
        }

        return true;
    }
}