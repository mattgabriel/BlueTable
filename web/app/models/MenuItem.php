<?php

require_once 'BaseModel.php';

class MenuItem extends BaseModel 
{
    public $AutoId;
    public $MenuItemId;
    public $MenuId;
    public $MenuItemName;
    public $MenuItemDesciption;
    public $MenuItemPrice;
    public $MenuItemImage;
    public $MenuItemProtein;
    public $MenuItemCarbs;
    public $MenuItemFat;
    public $MenuItemIsSpicy;
    public $MenuItemIsVegetarian;
    public $MenuItemIsVegan;
    public $MenuItemIsGlutenFree;
    public $MenuItemGroupId;
    public $MenuItemStatus;
    
    CONST MENU_ITEM_STATUS_AVAILABLE = 1;
    CONST MENU_ITEM_STATUS_OUT_OF_STOCK = 2;
	
    public function __construct() {
        //call parent with primary key name "AutoId", table name "Users"
        //and function that returns the pdo handler named "getdbh"
        parent::__construct('AutoId', 'MenuItem');
    }
}