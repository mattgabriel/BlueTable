<?php

require_once 'DBObject.php';
require_once 'IDataModifier.php';
require_once 'QueryBuilder.php';

abstract class BaseModel implements IDataModifier{
    protected $db;
    private $columns;
    
    function __construct($pkname, $tablename) {
        $this->db = new DBObject($pkname, $tablename);
    }
    
    public function getColumnNames(){
        if(!isset($this->columns))
            $this->columns = array_keys(call_user_func('get_object_vars',$this));
        
        return $this->columns;
    }
    
    public function resetObject()
    {
        $this->db->rs = array();
    }
    
    public function update($data)
    {
        $this->resetObject();
        $this->setData($data);
        return $this->db->update();
    }
    
    public function insert($data)
    {
        
        $this->resetObject();
        
        //Set auto increment pk
        if(is_array($data)){
            $data[$this->db->getPKName()]=null;
        } else {
            $key = $this->db->getPKName();
            $data->$key = null;
        }

        $this->setData($data);
        return $this->db->create();
    }
    
    public function delete($data)
    {
        $this->resetObject();
        $this->setData($data);
        return $this->db->delete();
    }
    
    public function select($query)
    {
        $result = $this->db->anyQuery($query);
        return $result;
    }
    
    private function setData($data){
        //accept arrays or objects
        
        if(is_array($data)) {
            $this->setValuesFromArray($data);
        }
        else if(is_a ($data, get_class($this))) {
            $this->setValuesFromObject($data);
        }
    }
    
    // Should use html entities and addslashes before storing?
    private function setValuesFromArray($arr){
        foreach($arr as $key=>$value)
        {
            $this->db->set($key, $value);
        }
    }
    
    private function setValuesFromObject($obj){
        
        foreach($this->getColumnNames() as $key)
        {
            $this->db->set($key, $obj->$key);
        }
    }
    
    public function begin(){
        $this->db->startTransaction();
    }

    public function apply(){
        $this->db->endTransaction();
    }

    public function discard(){
        $this->db->abandonTransaction();
    }
    
    protected function arr2obj($arr)
    {
        return json_decode(json_encode($arr));
    }
}
