<?php

namespace Palmo\source\validation;

use Palmo\source\validation\interfaces\ValidatorInterface;
class Validator
{
    private $validators = [];

    public function addValidator($fieldType, ValidatorInterface $validator) {
        $this->validators[$fieldType] = $validator;
    }

    public function validate(array $data)
    {
        $errors = [];
        foreach ($data as $fieldName => $item) {
            $validator = $this->validators[$item['type']];
            
            if ($validator) {
                if (! $validator->validate($item['data'])) {
                    $errors[$fieldName] = $validator->getError();
                }

            } else {
                $type = $item['type'];
                $errors[$type] = "Validator for this type {$type} is absent";
            }

        }

        return $errors;
    }
}