<?php

namespace Palmo\source\validation\validators;

class FloatValidator extends BaseValidator
{
    private $min;
    private $max;

    public function __construct($min = 0, $max = PHP_FLOAT_MAX) 
    {
        $this->min = $min;
        $this->max = $max;
    }
    public function validate($value): bool
    {

        if (!filter_var($value, FILTER_VALIDATE_FLOAT)) {
            $this->errorMessage = "Value must be float  number";
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