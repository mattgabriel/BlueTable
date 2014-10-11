<?php

require_once 'BaseModel.php';

class RestaurantsModel extends BaseModel 
{
    public $AutoId;
    public $RestaurantId;
    public $ManagerUserId;
    public $Name;
    public $Latitude;
    public $Longitude;
    public $DateCreated;
	
    public function __construct() {
        //call parent with primary key name "AutoId", table name "Users"
        //and function that returns the pdo handler named "getdbh"
        parent::__construct('AutoId', 'Restaurants');
    }
}