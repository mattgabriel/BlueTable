<?php

require_once 'BaseModel.php';

class Users extends BaseModel {
    public $AutoId;
    public $UserId;
    public $Username;
    public $Password;
    public $FirstName;
    public $LastName;
    public $Email;
	
    function __construct(){
            //call parent with primary key name "AutoId", table name "Users"
            //and function that returns the pdo handler named "getdbh"
            parent::__construct('AutoId','Users');
    }
}