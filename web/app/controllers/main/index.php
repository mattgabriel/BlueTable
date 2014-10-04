<?php
//path: 		main/index.php
//class name: 	indexClass
define('PAGE_NAME','index');
define('MODEL_NAME','Users');
define('PAGE_PATH','main/index');
require_once(APP_PATH.'models/'.MODEL_NAME.'.php');

//loads the class below
function _index() {
	$className = PAGE_NAME . 'Class';
	$return = new $className();
	return $return;
}

class indexClass extends Users{
	public $users;
	public $library;
	public $error;
			
	function __construct(){
				
		$view = new View(APP_PATH.'views/layout.php');
		require_once(APP_PATH.'inc/head.php');
		$head = new headClass();
		require_once(APP_PATH.'inc/header.php');
		$header = new headerClass();
		require_once(APP_PATH.'inc/footer.php');
		$footer = new footerClass();
		require_once(APP_PATH.'library/library.php');
		$this->library = new libraryClass();
		require_once(APP_PATH.'library/config.php');
		$this->config = new configClass();
		$this->users = new Users();

		//set output
		$view->set('head',$head->displayHead(null,null,null,null,''));
		$view->set('header',$header->displayHeader());
		$view->set('footer',$footer->displayFooter());
		$view->set('content',$this->content());
		$view->dump();
	}
	
	function content(){	
			
		return 'Home page';
	}
	
	
	
}
