<?php

require_once 'BaseModel.php';

class UserProfileModel extends BaseModel{
    public $AutoId;
    public $UserId;
    public $PayPalId;
    public $JustGivingId;
            
    public function __construct() {
        //call parent with primary key name "AutoId", table name "Users"
        //and function that returns the pdo handler named "getdbh"
        parent::__construct('AutoId', 'UserProfile');
    }
}
