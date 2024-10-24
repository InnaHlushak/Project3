<?php

namespace Palmo\source\search;

use Palmo\source\search\Items;
use Palmo\source\store\ItemsByCountRepository;

class ItemsByCount extends Items {
    protected $params = [
        'count'   => '',
    ];

    protected $pagination = [
            'per_page'  => 4,
            'page'   => '',
            'total_pages' => '',
    ];

    public function __construct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ( isset($_POST['count']) && is_numeric($_POST['count']) && $_POST['count'] > 0) {
                $this->params['count'] = filter_var($_POST['count'],FILTER_VALIDATE_INT);              
            } 
        }

         if ($_SERVER['REQUEST_METHOD'] === 'POST') {          
            if (isset($_POST['page'])) {
                $this->pagination['page'] = $_POST['page'];
            } 
        }

        $itemsRepository = new ItemsByCountRepository();
        $itemsRepository->setParams($this->params);
        $itemsRepository->setPage($this->pagination['page']);
        $this->items = $itemsRepository->selectItems();               
        $this->pagination['total_pages'] = $itemsRepository->getTotalPages();
    }

    public function getPages() {
        return $this->pagination['total_pages'];
    }
}