<?php
namespace Palmo\source\traits;

trait FilterByExplanation
{
    public function getParams()
    {
        $params = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $value = $_POST['explanation'];

            if (is_string($value)  && !empty(trim($value))) { 
                $params = htmlspecialchars($value);
            } 
        }
     
        return $params;
    }
}