<?php

require_once 'BaseModel.php';

class Order extends BaseModel 
{
    public $AutoId;
    public $RestaurantId;
    public $OrderId;
    public $UserId;
    public $TableId;
    public $MenuItemsIds;
    public $TotalPrice;
    public $Date;

    public function __construct() {
        //call parent with primary key name "AutoId", table name "Users"
        //and function that returns the pdo handler named "getdbh"
        parent::__construct('AutoId', 'Order');
    }
}