<?php
//path: 		main/index_actions.php
define('PAGE_NAME','RestaurantDetails_actions');
define('PAGE_PATH','admin/RestaurantDetails_actions');
//require_once(APP_PATH.'models/'.MODEL_NAME.'.php');

//loads the class below
function _RestaurantDetails_actions() {
    $className = PAGE_NAME . 'Class';
    $return = new $className();
    print_r($return);
//    return $return;
}

class RestaurantDetails_actionsClass {
    public $library;
    public $config;
    public $users;
    public $tables;

    function __construct(){
        $this->tables = new Tables();


        //if there is an action attached as POST
        if(isset($_POST['Action'])){
            $Action = $_POST['Action'];
            //check if the passed method exists and if it does call it
            if(method_exists($this, $Action)){
                $this->{$Action}();
            } else { echo 'Error: method not found.' . $Action; } 
        } else { echo 'Error: no action found.' . $Action; }

    }

    public function test() {
        return 'someMethod called';
    }
		
    public function retrieveUserAtTables() {
        $query = '
            SELECT t.* 
            FROM Tables t
            LEFT JOIN UserAtTable uat
                ON uat.TableId = t.TableId
            WHERE uat.AutoId IS NULL';
        //$freeTables = $this->tables->select($query);
        
        echo 'sagasgsasa';
    }
    
}
_RestaurantDetails_actions();