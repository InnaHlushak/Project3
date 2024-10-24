<?php

namespace Palmo\source\store;
use PDO;
class ItemsByDateRepository extends ItemsRepository 
{
    private $date;

    public function setParams($params) {
        $this->date = $params;
    }

    public function selectItems()
    {
        // Визначення дати для фільтрації
        $date = $this->date;

        $sql = "SELECT * FROM items WHERE DATE(creation_date) = '$date'";

        // Виконання запиту і отримання даних
        $result = $this->dbh->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}