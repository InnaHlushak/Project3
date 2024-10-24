<?php

namespace Palmo\source\store;
use Palmo\source\Db;
use PDO;
class MediaRepository {
    private $dbh;
    public function __construct() {
        $this->dbh = (new Db())->getHandler();
    }

    public function getMedia()
    {
        return $this->dbh->query("SELECT * FROM media")->fetchAll(PDO::FETCH_ASSOC);

    }
}