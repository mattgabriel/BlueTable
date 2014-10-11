<?php

require_once 'restService.php';
require_once APP_PATH . 'models/UserModel.php';

class UserModel extends restService {
    function __construct($params){
        parent::__construct($params);
    }
    
    protected function _setServiceName() {
        
    }
    
    private function putUser($params){
        //Login with paypal
    }
    
    private function getLogin($params){
        $args = $params[ParamTypes::QUERY_STR];
        
    }
    
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
}
