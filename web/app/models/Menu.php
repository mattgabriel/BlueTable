<?php

require_once 'BaseModel.php';

class Menu extends BaseModel 
{
    public $AutoId;
    public $RestaurantId;
    public $MenuId;
	
    public function __construct() {
        //call parent with primary key name "AutoId", table name "Users"
        //and function that returns the pdo handler named "getdbh"
        parent::__construct('AutoId', 'Menu');
    }
}