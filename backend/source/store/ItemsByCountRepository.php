<?php

namespace Palmo\source\store;

use PDO;
class ItemsByCountRepository extends ItemsRepository 
{
    private $params = [
        'count' => '',
    ];
    private $pagination = [
            'per_page'  => 4,
            'page'   => '',
            'total_pages' => '',
            'total_items' => '',
    ];

    public function setParams($params) {
        $this->params = $params;
    }
    
    public function setPage($page) {
        $this->pagination['page'] = $page;
    }

    public function getTotalPages() {
        return $this->pagination['total_pages'];
    }

    public function selectItems(){
        $count = (int)$this->params['count']; // Фільтр за кількістю
        $page = max((int)$this->pagination['page'],1); // Поточна сторінка або сторінка 1
        $per_page = $this->pagination['per_page']; // Кількість записів на сторінку
        $offset = ($page - 1) * $per_page; // Зміщення для SQL-запиту

        $sql = "SELECT * FROM (
                SELECT * FROM items
                ORDER BY RAND()";

        if (!empty($count)) {            
            $sql .= "LIMIT $count"; 
            $this->pagination['total_items'] = $count; 
        } 

        $sql .= ") AS random_items
            ORDER BY creation_date DESC
            LIMIT $per_page
            OFFSET $offset";

        //обчислення кількості сторінок пагінатора
        $total_pages = ceil( (int)$this->pagination['total_items'] / $per_page); 
        $this->pagination['total_pages'] = $total_pages;
        
        // Виконання запиту і отримання даних
        $result = $this->dbh->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}