<?php

namespace Palmo\source\validation\validators;

class NumberValidator extends BaseValidator
{
    private $min;
    private $max;

    public function __construct($min = 0, $max = PHP_INT_MAX) 
    {
        $this->min = $min;
        $this->max = $max;
    }
    public function validate($value): bool
    {
        if(!is_numeric($value) ) {
            $this->errorMessage = "Value must be a number";
            return false;
        }

        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            $this->errorMessage = "Value must be integer  number";
            return false;
        }

        $value = (float)$value;
        if($value < $this->min || $value > $this->max) {
            $this->errorMessage = "Value must be between" . $this->min . " and " . $this->max;
            return false;
        }

        return true;
    }
}