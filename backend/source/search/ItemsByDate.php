<?php
namespace Palmo\source\search;

use Palmo\source\search\Items;
use Palmo\source\store\ItemsByDateRepository;

class ItemsByDate extends Items {
    public function __construct() {
        $currentDate = date('Y-m-d');
        $this->params = $currentDate; 

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $value = $_POST['date'];

            if (!empty($value) && $value <= $currentDate) { 
                $this->params = $value;
            } 
        }

        $itemsRepository = new ItemsByDateRepository();
        $itemsRepository->setParams($this->params);
        $this->items = $itemsRepository->selectItems();
    }
}