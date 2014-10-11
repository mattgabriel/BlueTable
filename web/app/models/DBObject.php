<?php

class DBObject Extends Model {
    
    //Instantiate model and create class?
    public function setPKName($pkname){
        $this->pkname = $pkname;
    }
    
    public function setTableName($tablename){
        $this->tablename = $tablename;
    }
    
    public function getPKName(){
        return $this->pkname;
    }
}
