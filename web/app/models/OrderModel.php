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
    
    
    public function closeOrder($orderId) {
        $query = "
            UPDATE `Order`
                SET Status = " . OrderModel::STATUS_CLOSED . "
            WHERE OrderId = '" . $orderId . "'";
        
        $this->select($query);
                
        $menuItemInOrder = new MenuItemInOrder();
        $priceResult = $menuItemInOrder->getTotalPriceForOrder($orderId);
        if (!empty($priceResult[0]['TotalPrice'])) {
            $query = "
                UPDATE `Order`
                    SET TotalPrice = " . (double)$priceResult[0]['TotalPrice'] . "
                WHERE OrderId = '" . $orderId . "'";
        }
        return $this->select($query);
    }
}