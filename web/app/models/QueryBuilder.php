<?php

class QueryBuilder {
    private $query;
    
    //All clause properties are dynamically created
    //If array is used, can store and/or structure along with subqueries
    //Array can be array of QueryItem objects?

    function __construct() {
        $this->query = null;
        return $this;
    }
    
    public function getClause($clause)
    {
        $return = null;
        $property = $this->clauseToProperty($clause);
        if(property_exists($this, $property))
                $return = $this->$property;
        
        return $return;
    }
    
    public function get()
    {
        return $this->query;
    }
    
    public function set($query)
    {
        $this->query = $query;
        return $this;
    }
    
    public function select($selectors){
        return $this->addQuery("SELECT", $selectors);
    }
    
    public function from($record){
        return $this->addQuery("FROM", $record);
    }
    public function limit($limit){
        return $this->addQuery("LIMIT", $limit);
    }
    public function join($record, $onwhat){
        return $this->addQuery("JOIN", $onwhat);
    }
    
    public function on($onwhat){
        return $this->addQuery("ON", $onwhat);
    }
    
    public function where($wherewhat){
        return $this->addQuery("WHERE", $wherewhat);
    }
    
    public function andIf($andwhat){
        return $this->addQuery("AND", $andwhat);
    }
    
    public function orIf($orwhat){
        return $this->addQuery("OR", $orwhat);
    }
    
    public function first(){
        return $this->addQuery("LIMIT", "1");
    }
    
    public function orderby($orderonwhat){
        return $this->addQuery("ORDER BY", $orderonwhat);
    }
    
    public function groupby($grouponwhat){
        return $this->addQuery("GROUP BY", $grouponwhat);
    }
    
    private function addQuery($keyword, $value){
        $property = $this->clauseToProperty($keyword);
        $this->$property = $value;
        $this->query .= ' ' . $keyword . ' ' . $value;
        return $this;
    }
    
    private function clauseToProperty($clause)
    {
        $pname = 'Q' . str_replace(' ','',strtolower($clause));
        return $pname;
    }
}
