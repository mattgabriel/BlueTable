<?php
//path: 		api/v1.php
//class name: 	v1Class
define('PAGE_NAME','v1');
//define('MODEL_NAME','Users');
define('PAGE_PATH','api/v1');
//require_once(APP_PATH.'models/'.MODEL_NAME.'.php');

//loads the class below
function _v1($param1='',$param2='',$param3='') {
	$className = PAGE_NAME . 'Class';
	$return = new $className($param1,$param2,$param3);
	return $return;
}

/*
Simple request
curl -X POST -d 'UserId=user&Token=value2' http://166.78.145.139/api/v1

curl -H "Accept: application/json" -H "Content-type: application/json" -X GET -d '{"first_name":"firstname","email":"email@email.com"}' http://166.78.145.139/api/v1

curl -H "Accept: application/json" -H "Content-type: application/json" -X POST -d 'test=test1' http://166.78.145.139/api/v1

GET: curl http://166.78.145.139/api/v1/users
*/



class v1Class {
	public $Users;
	public $library;
	public $error;
	
	/**
     * Property: method
     * The HTTP method this request was made in, either GET, POST, PUT or DELETE
     */
    protected $method = '';
    /**
     * Property: endpoint
     * The Model requested in the URI. eg: /files
     */
    protected $endpoint = '';
    /**
     * Property: verb
     * An optional additional descriptor about the endpoint, used for things that can
     * not be handled by the basic methods. eg: /files/process
     */
    protected $verb = '';
    /**
     * Property: args
     * Any additional URI components after the endpoint and verb have been removed, in our
     * case, an integer ID for the resource. eg: /<endpoint>/<verb>/<arg0>/<arg1>
     * or /<endpoint>/<arg0>
     */
    protected $args = Array();
    /**
     * Property: file
     * Stores the input of the PUT request
     */
    protected $file = Null;
	
	protected $request;
	
	public $param1;
	public $param2;
	public $param3;
			
	function __construct($param1='',$param2='',$param3=''){
		header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");
        
		require_once(APP_PATH.'library/library.php');
		$this->library = new libraryClass();
		require_once(APP_PATH.'library/config.php');
		$this->config = new configClass();
		
		$this->param1 = $param1;
		$this->param2 = $param2;
		$this->param3 = $param3;
		
		$this->Users = new Users();
		
		//set output
		//$this->performRequest();
		
		$this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $this->method = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $this->method = 'PUT';
            } else {
                throw new Exception("Unexpected Header");
            }
        }
        		
		
        switch($this->method) {
        case 'DELETE':
        	$this->request = $this->_cleanInputs($_GET);
        	$this->callMethod();
            break;
        case 'POST':
            $this->request = $this->_cleanInputs($_POST);
            $this->callMethod();
            break;
        case 'GET':
            $this->request = $this->_cleanInputs($_GET);
            $this->callMethod();
            break;
        case 'PUT':
            $this->request = $this->_cleanInputs($_GET);
            $this->file = file_get_contents("php://input");
            $this->callMethod();
            break;
        default:
            $this->_response('Invalid Method', 405);
            break;
        }	
        
	}
	
	
	//curl http://x/api/v1/USER/UserId/{Details|Photo|LastLocation|}
	//curl http://x/api/v1/USER/VALIDATE/ {get: email/username; password}
	private function getUser(){
		//results depend on param2
		if($this->param2 == 'VALIDATE'){
			$this->validateUser($this->request);
		} else {
			if($this->param3 == 'Details'){
				echo "Details!!!!\n";
				print_r(json_encode($this->request));
			} elseif($this->param3 == 'Photo'){
				echo "Photo!!!!\n";
			} elseif($this->param3 == 'LastLocation'){
				echo "LastLocation!!!!\n";
			} else {
				$this->_response('Invalid Method', 405);
			}
		}
	}
	//curl -d "name=matt&email=matt@matt.com" http://x/api/v1/DROP
	private function postUser(){
		//echo "Posting drop\n";
		//print_r(json_encode($this->request));
		
		$UserId 		= $this->library->generatePassword(10);
		$Password 		= $this->request['password'];
		$Username 		= $this->request['username'];
		$Email 			= $this->request['email'];
		$FirstName 		= '';
		$LastName 		= '';
		
		$isUser = $this->Users->retrieveCustomRows('Username = ?',$Username);
		if($isUser){
			//user already exists
			echo json_encode(array(	'Result' => 'Error', //Success/Error
									'Details' => $Username . ' is already taken.'));
		} else {
			//create the user
			$this->Users->createRow($UserId,$Username,$Password,$FirstName,$LastName,$Email);
			$this->UserStats->createRow($UserId);
			echo json_encode(array(	'Result' => 'Success', //Success/Error
									'UserId' => $UserId,
									'Username' => $Username,
									'FirstName' => $FirstName,
									'LastName' => $LastName,
									'Email' => $Email));
		}
	}
	function validateUser($request){
		$email = addslashes(strip_tags($request['email']));
		$password = addslashes(strip_tags($request['password'])); //dont forget to dm5 password!!!
		
		//check if user exists
		$isUser = $this->Users->retrieveCustomRows('Email = ? AND Password = ?',array($email,$password));
		if($isUser){
			echo json_encode(array(	'Result' => 'Success', //Success/Error
									'UserId' => $isUser->UserId,
									'Username' => $isUser->Username,
									'FirstName' => $isUser->FirstName,
									'LastName' => $isUser->LastName,
									'Email' => $isUser->Email));
		} else {
			echo json_encode(array(	'Result' => 'Error', //Success/Error
									'Details' => 'User not found'));
		}
	}
		
	//curl -X DELETE http://x/api/v1/USER/UserId
	private function deleteUser(){
		if($this->param2 != '' && $this->param2 != ' '){
			echo 'Deleting a user';
		} else {
			echo $this->_response('Invalid Method', 405);
		}
	}
	
	//curl -X PUT -d "job=developer&test=yes" http://x/api/v1/USER/UserId
	private function putUser(){
		if($this->param2 != '' && $this->param2 != ' '){
			echo 'updating a user';
			$data = explode("&",$this->file);
			$allData = array();
			foreach($data as $item){
				$x = explode("=",$item);
				$allData[trim(strip_tags($x[0]))] = trim(strip_tags($x[1]));
			}
			//print_r($allData);
			echo 'Updating user';
		} else {
			echo $this->_response('Invalid Method', 405);
		}
	}
	
	
	
	
	private function callMethod(){
		$methodToCall = $this->methodName($this->method,$this->param1);
		if(method_exists($this, $methodToCall)){
	       	$this->{$methodToCall}();
	    } else {
		    echo 'Undefined method';
	    }
	}
	//autogenerate method name to call after receiving cURL request
	private function methodName($method,$name){
		$methodType = strtolower($method); //delete/put
		$methodName = ucfirst($name);
		return $methodType . $methodName; //eg: deleteUser
	}
	
	private function _response($data, $status = 200) {
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        return json_encode($data);
    }
    

    private function _cleanInputs($data) {
        $clean_input = Array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->_cleanInputs($v);
            }
        } else {
            $clean_input = trim(strip_tags($data));
        }
        return $clean_input;
    }


    private function _requestStatus($code) {
        $status = array(  
            200 => 'OK',
            404 => 'Not Found',   
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        ); 
        return ($status[$code])?$status[$code]:$status[500]; 
    }
	
	function performRequest(){	
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			print_r($_POST);
			$return = 'POST is set ';
		} elseif($_SERVER['REQUEST_METHOD'] === 'GET'){
			$return = 'GET is set ';
		} elseif($_SERVER['REQUEST_METHOD'] === 'PUT'){
			$return = 'PUT is set ';
		} elseif($_SERVER['REQUEST_METHOD'] === 'DELETE'){
			$return = 'DELETE is set ';
		} else {
			$return = 'Nothing';
		}
		
		echo $return;
	}
	
	
	
}
