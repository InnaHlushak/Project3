<?php

namespace Palmo\source\validation\validators;

use DateTime;

class DateValidator extends BaseValidator
{
    public function validate($value): bool
    {
        $date = DateTime::createFromFormat('Y-m-d', $value);
        $current_date = new DateTime("now");
        if(!$date || !($date->format('Y-m-d') === $value) || $date > $current_date) {
            $this->errorMessage = "Invalid date";
            return false;
        }
  
        return true;
    }
}