<?php

namespace Palmo\source\validation\validators;

class FileValidator extends BaseValidator
{
    public function validate($file): bool
    {

        if (!$file['error'] === UPLOAD_ERR_OK) {
            $this->errorMessage = "The file could not be downloaded or an error occurred";
            return false;
        } 

        if (!in_array($file['type'], ['image/jpeg', 'image/png', 'application/pdf'])) {
            $this->errorMessage = "File type not allowed. Allowed: jpeg, png, pdf";
            return false;
        }
     
        return true;
    }
}