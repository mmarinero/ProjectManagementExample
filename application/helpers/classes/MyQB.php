<?php

/**
 * QueryBuilder implementation for MySql capable of executing queries directly
 * Result for fetch/fetchAll is the same as executing the same functions on PDO
 * with PDO::ATTR_DEFAULT_FETCH_MODE = PDO::FETCH_ASSOC
 * This class implements the parts of AbstractQueryBuilder that depends in the 
 * DataBase access library.
 */
class MyQB extends AbstractQueryBuilder{
    
    private $link;
    
    function parse($params){
        
    }
    
    function fetchAll(){
        
    }
    
    function fetch(){
        
    }

    public function quote($param) {
        return "'".mysqli_real_escape_string($this->link, $param)."'";
    }

    public function quoteIdentifier($param) {
        return '`'.str_replace(array('`'),array('``'), $param).'`';
    }
}