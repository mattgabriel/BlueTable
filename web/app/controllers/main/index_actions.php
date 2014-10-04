<?php
//path: 		main/index_actions.php
//class name: 	index_actionsClass
define('PAGE_NAME','index_actions');
//define('MODEL_NAME','Users');
define('PAGE_PATH','main/index_actions');
//require_once(APP_PATH.'models/'.MODEL_NAME.'.php');


//loads the class below
function _index_actions() {
	$className = PAGE_NAME . 'Class';
	$return = new $className();
	return $return;
}

class index_actionsClass {
	public $library;
	public $config;
	public $users;
	
	function __construct(){
		require_once(APP_PATH.'library/library.php');
		$this->library = new libraryClass();
		require_once(APP_PATH.'library/config.php');
		$this->config = new configClass();
		require_once(APP_PATH.'models/Users.php');
		$this->users = new Users();
		
		
		//if there is an action attached as POST
		if(isset($_POST['Action'])){
			$Action = $_POST['Action'];
			//check if the passed method exists and if it does call it
			if(method_exists($this, $Action)){
				$this->{$Action}();
			} else { echo 'Error: method not found.' . $Action; } 
		} else { echo 'Error: no action found.' . $Action; }
		
	}
	
	function someMethod(){
		return 'someMethod called';
	}
		
}
