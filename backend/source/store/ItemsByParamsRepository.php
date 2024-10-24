<?php

namespace Palmo\source\store;

use PDO;

class ItemsByParamsRepository extends ItemsRepository 
{
    private $params = [
        'start_date'   => '',
        'end_date'     => '',
        'title'        => '',
        'explanation'  => '',
        'media_type'   => '',
    ];

    protected $pagination = [
        'per_page'  => 4,
        'page'   => '',
        'total_pages' => '',
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

    public function selectItems()
    {
        //Параметри для фільтрів
        $start_date = $this->params['start_date'];
        $end_date = $this->params['end_date'];
        $title = $this->params['title'];
        $explanation = $this->params['explanation'];
        $media_type = $this->params['media_type'];
        $offset = $this->params['offset'];
        //Параметри для пагінації
        $page = max((int)$this->pagination['page'],1); // Поточна сторінка або сторінка 1
        $per_page = $this->pagination['per_page']; // Кількість записів на сторінку
        $offset = ($page - 1) * $per_page; // Зміщення для SQL-запиту

        $parameters = []; //масив для зв'язування параметрів з плейсхолдерами у запитах

        // Базовий запит для фільтрування записів з БД
        $sql = 'SELECT * FROM items 
        INNER JOIN media ON items.media_id=media.id
        WHERE 1 = 1'; 
        
        // Базовий запит для підрахунку відфільтрованих записів з БД
        $sql_count = 'SELECT COUNT(*) FROM items 
        INNER JOIN media ON items.media_id=media.id
        WHERE 1 = 1';

        // Якщо задана початкова дата
        if (!empty($start_date) && empty($end_date)) {
            $request = ' AND creation_date >= :start_date';
            $sql .= $request;
            $sql_count .= $request;
            $parameters[':start_date'] = $start_date;
        }

        // Якщо задана кінцева дата
        if (empty($start_date) && !empty($end_date)) {
            $request = ' AND creation_date <= :end_date';
            $sql .= $request;
            $sql_count .= $request;
            $parameters[':end_date'] = $end_date;
        }

        // Якщо задані обидві дати
        if (!empty($start_date) && !empty($end_date)) {
            $request = ' AND creation_date BETWEEN :start_date AND :end_date';
            $sql .= $request;
            $sql_count .= $request;
            $parameters[':start_date'] = $start_date;
            $parameters[':end_date'] = $end_date;
        }

        // Якщо заданий заголовок
        if (!empty($title)) {
            $request = ' AND title LIKE CONCAT("%", :title, "%")';
            $sql .= $request;
            $sql_count .= $request;
            $parameters[':title'] = $title;
        }

        // Якщо заданий опис-пояснення
        if (!empty($explanation)) {
            $request = ' AND explanation LIKE CONCAT("%", :explanation, "%")';
            $sql .= $request;
            $sql_count .= $request;
            $parameters[':explanation'] = $explanation;
        }

        // Якщо обрано медіа-тип
        if (!empty($media_type)) {
            $request = ' AND  media.type = :media_type';
            $sql .= $request;
            $sql_count .= $request;
            $parameters[':media_type'] = $media_type;
        }

        // Додавання умов для сортування та обмеження кількості записів
        $sql .= ' ORDER BY creation_date DESC LIMIT :per_page OFFSET :offset';
        // Підготовка запиту
        $stmt = $this->dbh->prepare($sql);
        // Прив'язуємо параметри  з відповідними плейсхолдерами
        foreach ($parameters as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':per_page', $per_page);
        $stmt->bindValue(':offset', $offset);
        // Виконання запиту
        $stmt->execute();
        $result_items = $stmt->fetchAll(PDO::FETCH_ASSOC);


        //Обчислення загальної кількості сторінок для пагінатора:
        //Підготовка запиту
        $stmt_count = $this->dbh->prepare($sql_count);
        // Прив'язуємо параметри  з відповідними плейсхолдерами
        foreach ($parameters as $key => $value) {
            $stmt_count->bindValue($key, $value);
        }
        //Виконання запиту
        $stmt_count->execute();
        $total_items = (int)$stmt_count->fetchColumn();
        $total_pages = ceil($total_items / $per_page); 
        $this->pagination['total_pages'] = $total_pages;

        return $result_items;
    }
}