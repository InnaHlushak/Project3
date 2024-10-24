<?php
namespace Palmo\source\traits;

trait FilterByTitle
{
    public function getParams()
    {
        $params = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $value = $_POST['title'];

            if (is_string($value)  && !empty(trim($value))) { 
                $params = htmlspecialchars($value);
            } 
        }
     
        return $params;
    }
}