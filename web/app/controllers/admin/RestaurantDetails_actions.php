<?php
//path: 		main/index_actions.php
define('PAGE_NAME','RestaurantDetails_actions');
define('PAGE_PATH','admin/RestaurantDetails_actions');
//require_once(APP_PATH.'models/'.MODEL_NAME.'.php');

//loads the class below
function _RestaurantDetails_actions() {
    $className = PAGE_NAME . 'Class';
    $return = new $className();
    return $return;
}

class RestaurantDetails_actionsClass {
    public $library;
    public $config;
    public $users;
    public $tables;

    function __construct(){
        require_once dirname(dirname(__DIR__)) . '/models/TableModel.php';
        $this->tables = new TableModel();

        //if there is an action attached as POST
        if(isset($_POST['Action'])){
            $Action = $_POST['Action'];
            //check if the passed method exists and if it does call it
            if(method_exists($this, $Action)){
                $this->{$Action}();
            } else { echo 'Error: method not found.' . $Action; } 
        } else { echo 'Error: no action found.';}

    }
    
    public function retrieveRestaurantStatus() {
        $freeTables = $this->_retrieveFreeTables();
        $occupiedTables = $this->_retrieveUserAtTables();
        $income = $this->_retrieveTodaysIncome();
        
        $restaurantStatus = array(
            'freeTables' => $freeTables,
            'occupiedTables' => $occupiedTables,
            'income' => $income
        );
        echo json_encode($restaurantStatus);
    }

    private function _retrieveFreeTables() {
        $query = '
            SELECT t.* 
            FROM Tables t
            LEFT JOIN UserAtTable uat
                ON uat.TableId = t.TableId
            WHERE uat.AutoId IS NULL';
        return $this->tables->select($query);
    }
    
    private function _retrieveUserAtTables() {
        $query = '
            SELECT * 
            FROM Tables t
            INNER JOIN UserAtTable uat
                ON uat.TableId = t.TableId
            INNER JOIN User u
                ON u.UserId = uat.UserId';
        return $this->tables->select($query);
    }
    
    private function _retrieveTodaysIncome() {
        $query = "
            SELECT * 
            FROM `Order` o 
            INNER JOIN `Tables` t
                ON t.TableId = o.TableId
            INNER JOIN `User` u
                ON u.UserId = o.UserId
            WHERE Status = " . OrderModel::STATUS_CLOSED;
        return $this->tables->select($query);
    }
    
}