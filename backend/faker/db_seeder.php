<?php

use Palmo\source\Db;

require_once '../vendor/autoload.php';

set_time_limit(0);

$dbh = (new Db())->getHandler();

$faker = Faker\Factory::create();
$faker->addProvider(new Faker\Provider\en_US\Address($faker));
$faker->addProvider(new Faker\Provider\en_US\Person($faker));
$faker->addProvider(new Faker\Provider\en_US\Company($faker));
$faker->addProvider(new Faker\Provider\en_US\PhoneNumber($faker));
$faker->addProvider(new Faker\Provider\en_US\Text($faker));

if (!empty($_POST['items_amount'])) {
    $itemsAmount = $_POST['items_amount'];
    $mediaIds = $dbh->query("SELECT id FROM media")->fetchAll(PDO::FETCH_COLUMN);

    for ($i = 0; $i <= $itemsAmount; $i++) {
        $creationDate = $faker->date();

        $title = $faker->text(50);
        if (str_contains($title, "'")) {
            $title = str_replace("'", "\'", $title);
        }

        $explanation = $faker->realText(500);
        if (str_contains($explanation, "'")) {
            $explanation = str_replace("'", "\'", $explanation);
        }

        $url = $faker->url();

        $key = array_rand($mediaIds);
        $mediaId = $mediaIds[$key];

        $dbh->query("
                INSERT INTO items (creation_date, title, explanation, url, media_id)
                VALUES ('$creationDate', '{$title}', '{$explanation}', '{$url}', '{$mediaId}')
        ");
    }
}

header('Location: ../index.php');
