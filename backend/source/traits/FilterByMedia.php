<?php
namespace Palmo\source\traits;

trait FilterByMedia
{
    public function getParams()
    {
        $params = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $value = $_POST['media'];

            if (isset($value) && $value == 'image') {
                $params = $value;
            }

            if (isset($value) && $value == 'video') {
                $params = $value;
            }
        }
     
        return $params;
    }
}