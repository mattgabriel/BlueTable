<?php

require_once 'BaseModel.php';

class OrderModel extends BaseModel 
{
    public $AutoId;
    public $RestaurantId;
    public $OrderId;
    public $UserId;
    public $TableId;
    public $MenuItemsIds;
    public $TotalPrice;
    public $Status;
    public $Date;
    
    CONST STATUS_CLOSED = 2;
    CONST STATUS_OPEN = 1;

    public function __construct() {
        //call parent with primary key name "AutoId", table name "Users"
        //and function that returns the pdo handler named "getdbh"
        parent::__construct('AutoId', 'Order');
    }
}