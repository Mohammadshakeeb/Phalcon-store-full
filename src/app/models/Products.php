<?php

use Phalcon\Mvc\Model;

class Products extends Model
{
    public $description;
    public $name;
    public $stock;
    public $price;
    public $tags;
}