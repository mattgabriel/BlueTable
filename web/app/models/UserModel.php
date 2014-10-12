<?php

require_once 'BaseModel.php';

class UserModel extends BaseModel 
{
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
        parent::__construct('AutoId','User');
    }
    
    public function deleteUsersAtTable($userId) {
        $query = "
            DELETE FROM UserAtTable
            WHERE UserId = '" . $userId . "'";
        return $this->select($query);
    }
}