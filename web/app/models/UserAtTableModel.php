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
    
    CONST TABLE_STATUS_AVAILABLE = 0;
    CONST TABLE_STATUS_SITTING = 1;
    CONST TABLE_STATUS_DRINKS_SERVED = 2;
    CONST TABLE_STATUS_FOOD_SERVED = 3;
    CONST TABLE_STATUS_DESSERT_SERVED = 4;
    CONST TABLE_STATUS_PAID = 5;
    CONST TABLE_STATUS_AWAITING_CLEANING = 6;
    CONST TABLE_STATUS_ERROR = 99;
	
    public function __construct() {
        //call parent with primary key name "AutoId", table name "Users"
        //and function that returns the pdo handler named "getdbh"
        parent::__construct('AutoId', 'UserAtTable');
    }
    
    public function getMenuByUserId($userid)
    {
        $qb = new QueryBuilder();
        $qb->select('D.* FROM UserAtTable A
                     JOIN Tables B
                     ON A.TableId = B.TableId
                     JOIN Menu C
                     ON B.RestaurantId = C.RestaurantId
                     JOIN MenuItem D
                     ON C.MenuId = D.MenuId
                     WHERE A.UserID = "'.$userid.'"');
        return $this->select($qb->get());
    }
    
    public function getTableByTableId($TableId)
    {
        $qb = new QueryBuilder();
        $qb->select('*')->from('UserAtTable')->where('TableId = "'.$TableId.'"');
        $data = $this->select($qb->get());
        return $this->arr2obj($data);
    }
}