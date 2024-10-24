<?php

namespace Palmo\source\validation\validators;

class CheckboxValidator extends BaseValidator
{
    public function validate($value): bool
    {
        if(!isset($value) || !($value == "yes")) {
            $this->errorMessage = "Is  not specified";
            return false;
        }
        return true;
    }
}