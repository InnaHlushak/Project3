<?php

namespace Palmo\source\validation\validators;

class StringValidator extends BaseValidator
{
    private $minLength;
    private $maxLength;

    public function __construct($minLengt = 1, $maxLengt = 255) 
    {
        $this->minLength = $minLengt;
        $this->maxLength = $maxLengt;
    }
    public function validate($value): bool
    {
        if(!is_string($value) || empty(trim($value))) {
            $this->errorMessage = "Value must be a string";
            return false;
        }

        $length = strlen($value);
        if($length < $this->minLength || $length > $this->maxLength) {
            $this->errorMessage = "Must contain at least " . $this->minLength . " and no more than " . $this->maxLength . " characters";
            return false;
        }

        return true;
    }
}