<?php

namespace Palmo\source\validation\validators;
use Palmo\source\validation\interfaces\ValidatorInterface;

abstract class BaseValidator implements ValidatorInterface
{
    protected $errorMessage;
    public function getError(): string
    {
        return $this->errorMessage;
    }
}