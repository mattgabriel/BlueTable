<?php

require_once 'BaseModel.php';

class Tables extends BaseModel 
{
    public $AutoId;
    public $RestaurantId;
    public $TableId;
    public $TableNumber;
    public $TableStatus;

    CONST TABLE_STATUS_AVAILABLE = 0;
    CONST TABLE_STATUS_SITTING = 1;
    CONST TABLE_STATUS_DRINKS_SERVED = 2;
    CONST TABLE_STATUS_FOOD_SERVED = 3;
    CONST TABLE_STATUS_DESSERT_SERVED = 4;
    CONST TABLE_STATUS_PAYED = 5;
    CONST TABLE_STATUS_AWAITING_CLEANING = 6;
    
    public function __construct() {
        //call parent with primary key name "AutoId", table name "Users"
        //and function that returns the pdo handler named "getdbh"
        parent::__construct('AutoId', 'Tables');
    }
}