<?php
namespace Palmo\source\traits;

use DateTime;

trait FilterByPeriod
{
    public function getParams()
    {
        $params = [
            'start_date' => '',
            'end_date' => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $value = $_POST['start_date'];
            $start_date = DateTime::createFromFormat('Y-m-d', $value);

            if (!empty($value) && $start_date->format('Y-m-d') === $value) { 
                $params['start_date'] = $value;
            } 

            $value = $_POST['end_date'];
            $end_date = DateTime::createFromFormat('Y-m-d', $value);

            if (!empty($value) && $end_date->format('Y-m-d') === $value) { 
                $params['end_date'] = $value;
            } 
        }

        return $params;
    }
}