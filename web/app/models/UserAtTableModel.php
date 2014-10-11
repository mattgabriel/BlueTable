<?php

require_once 'BaseModel.php';
require_once 'QueryBuilder.php';

class UserAtTableModel extends BaseModel 
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
    
    public function getMenuByUserId($userid)
    {
        $qb = new QueryBuilder();
        $qb->select('C.* FROM UserAtTable A
                     JOIN Tables B
                     ON A.TableId = B.TableId
                     JOIN Menu C
                     ON B.RestaurantId = C.RestaurantId
                     JOIN MenuItem D
                     ON C.MenuId = D.MenuId
                     WHERE A.UserID = "'.$userid.'"');
        return $this->select($qb->get());
    }
}