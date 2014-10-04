<?php

class Users extends Model {
	
	function Users(){
		//call parent with primary key name "AutoId", table name "Users"
		//and function that returns the pdo handler named "getdbh"
		parent::__construct('AutoId','Users','getdbh');
		//list of table fields below, need not contain all fields in table.
		$this->rs['AutoId'] = '';
		$this->rs['UserId'] = '';
		$this->rs['Username'] = ''; 
		$this->rs['Password'] = '';
		$this->rs['FirstName'] = '';
		$this->rs['LastName'] = '';
		$this->rs['Email'] = ''; 
	}
	
	function createRow($UserId,$Username,$Password,$FirstName='',$LastName='',$Email=''){
		//Create
		$Data = new Users();
		$Data->set('UserId',$UserId);
		$Data->set('Username',$Username);
		$Data->set('Password',$Password);
		$Data->set('FirstName',$FirstName);
		$Data->set('LastName',$LastName);
		$Data->set('Email',$Email);
		$Data->create();
	}
	
	function updateRow__($column,$value){
		//Update
		$Data = new Users();
		$Data->set($column,$value);
		$Data->update();
	}
	
	function retrieveRow($UsersId){
		$Data = new Users();
		return $Data->retrieve($UsersId);
	}
	
	function retrieveCustomRows($where,$values){
		$Data = new Users();
		return $Data->retrieve_one($where,$values); 
		//("Usersname=? AND password=? AND status='enabled'",array('erickoh','123456'))
	}
	
	function retrieveManyRows($where,$values){
		$Data = new Users();
		return $Data->retrieve_many($where,$values);
		//("Usersname LIKE ?",'eric%')
	}
	
	function retrieveColumns($columns,$where,$values){
		$Data = new Users();
		return $Data->select($columns,$where,$values); 
		//returns array | $Users->select("Usersname,email","Usersname LIKE ?",'eric%');
	}
	
	function retrieveMixed($query){
		$Users = new Users();		
		return $Users->anyQuery($query);
	}
	
	function updateRow($col1,$val1,$col2,$val2){
		//update
		$Users = new Users();
		$query = "UPDATE Users SET $col1='$val1' WHERE $col2='$val2'";		
		$Users->freeStyle($query);
	}
	
	
}