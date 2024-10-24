<?php
namespace Palmo\source\search;

use Palmo\source\search\Items;
use Palmo\source\store\ItemsByParamsRepository;
use Palmo\source\traits\FilterByPeriod;
use Palmo\source\traits\FilterByTitle;
use Palmo\source\traits\FilterByExplanation;
use Palmo\source\traits\FilterByMedia;

class ItemsByParams extends Items 
{
    use FilterByPeriod, FilterByTitle, FilterByExplanation, FilterByMedia {
        FilterByPeriod::getParams insteadof FilterByTitle, FilterByExplanation, FilterByMedia;
        FilterByTitle::getParams as getTitle;
        FilterByExplanation::getParams as getExplanation;
        FilterByMedia::getParams as getMediaType;
    } 
    protected $params = [
        'start_date'   => '',
        'end_date'     => '',
        'title'        => '',
        'explanation'  => '',
        'media_type'   => '',
        'offset' => 0,
    ];

    protected $pagination = [
        'per_page'  => 4,
        'page'   => '',
        'total_pages' => '',
];


    public function __construct() {
        $period = $this->getParams();
        $this->params['start_date'] = $period['start_date'];
        $this->params['end_date'] = $period['end_date'];
        $title =  $this->getTitle();
        $this->params['title'] = $title;
        $explanation =  $this->getExplanation();
        $this->params['explanation'] = $explanation;
        $media = $this->getMediaType();
        $this->params['media_type'] = $media;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {          
            if (isset($_POST['page'])) {
                $this->pagination['page'] = $_POST['page'];
            } 
        }    

        $itemsRepository = new ItemsByParamsRepository();
        $itemsRepository->setParams($this->params);
        $itemsRepository->setPage($this->pagination['page']);
        $this->items = $itemsRepository->selectItems();
        $this->pagination['total_pages'] = $itemsRepository->getTotalPages();
    }

    public function getPages() {
        return $this->pagination['total_pages'];
    }

}