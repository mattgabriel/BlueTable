<?php

require_once 'BaseModel.php';

class BillModel extends BaseModel 
{
    public $AutoId;
    public $RestaurantId;
    public $BillId;
    public $UserId;
    public $OrderId;
    public $Price;
    public $Status;
    public $NeedToPrintReceipt;
    public $ReceiptWasPrinted;
    public $Date;

    CONST STATUS_AWAITING_PAYMENT = 0;
    CONST STATUS_PAYED = 1;
    CONST STATUS_CANCELLED_BY_RESTAURANT = 2;
    CONST STATUS_REFUNDED = 3;
    
    public function __construct() {
        //call parent with primary key name "AutoId", table name "Users"
        //and function that returns the pdo handler named "getdbh"
        parent::__construct('AutoId', 'Bill');
    }
}