<?php

require_once 'BaseModel.php';

class UserAtTable extends BaseModel 
{
    public $AutoId;
    public $UserId;
    public $TableId;
    public $SeatedTime;
    public $Status;
	
    public function __construct() {
        //call parent with primary key name "AutoId", table name "Users"
        //and function that returns the pdo handler named "getdbh"
        parent::__construct('AutoId', 'UserAtTable');
    }
}