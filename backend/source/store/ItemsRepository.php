<?php

namespace Palmo\source\store;
use Palmo\source\Db;
use PDO;
class ItemsRepository {
    protected $dbh;
    public function __construct() {
        $this->dbh = (new Db())->getHandler();
    }

    public function selectItems()
    {
        
        return $this->dbh->query("SELECT * FROM items")->fetchAll(PDO::FETCH_ASSOC);
    }
}