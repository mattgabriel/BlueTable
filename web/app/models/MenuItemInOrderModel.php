<?php

require_once 'BaseModel.php';

class MenuItemInOrder extends BaseModel 
{
    public $AutoId;
    public $MenuItemId;
    public $OrderId;
	
    public function __construct() {
        //call parent with primary key name "AutoId", table name "Users"
        //and function that returns the pdo handler named "getdbh"
        parent::__construct('AutoId', 'MenuItemInOrder');
    }
    
    public function getTotalPriceForOrder($orderId) {
        $query = "
            SELECT SUM(mi.MenuItemPrice) as TotalPrice
            FROM MenuItemInOrder miio
            INNER JOIN MenuItem mi
                ON mi.MenuItemId = miio.MenuItemId
            INNER JOIN `Order` o
                ON o.OrderId = miio.OrderId
            WHERE o.OrderId = '" . $orderId . "'";
        return $this->select($query);
    }
    
    public function deleteItemsByOrder($orderId) {
        $query = "
            DELETE FROM MenuItemInOrder
            WHERE OrderId = '" . $orderId . "'";
        return $this->select($query);
    }
    
    public function returnListOfItemsForOrder($orderId) {
        $query = "
            SELECT *
            FROM MenuItemInOrder miio
            INNER JOIN MenuItem mi
                ON miio.MenuItemId = mi.MenuItemId
            WHERE miio.OrderId = '" . $orderId . "'";
        return $this->select($query);
    }
}