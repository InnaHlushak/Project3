<?php 
namespace Palmo\source\search;
abstract class Items 
{
    protected  $items;
    protected $params;
    
    public function getItems() {
        return $this->items;
    }
}