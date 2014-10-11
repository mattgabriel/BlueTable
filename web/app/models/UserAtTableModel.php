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
    
    public function getMenuByTableId($tableid)
    {
        $qb = new QueryBuilder();
        $qb->select('C.* FROM UserAtTable A
                     JOIN Menu B
                     ON A.RestaurantId = B.RestaurantId
                     JOIN MenuItem C
                     ON B.MenuId = C.MenuId
                     WHERE A.TableId = "'.$tableid.'"');
        return $this->select($qb->get());
    }
}