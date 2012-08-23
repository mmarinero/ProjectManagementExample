<?php

/**
 * PHP query builder 
 */
Abstract class AbstractQueryBuilder {
    
    function get($table, $where = array()){
        
    }
    
    function insert($table, $fields){
        
    }
    
    function update($table, $fields, $where){
        
    }
    
    function delete($table, $where){
        
    }
    
    function select($fields){
        
    }
    
    function fromTable($table){
        
    }
    
    function from($tables){
        
    }
    
    function join($table, $condition){
        
    }
    
    function where($conditions){
        
    }
    
    function in($field, $list){
        
    }
    
    function orderBy($fields){
        
    }
    
    function groupBy($fields, $having){
        
    }
    
    function sql(){
        
    }
    
    abstract function quote($param);
    
    abstract function quoteIdentifier($param);
}


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

/**
 * QueryBuilder capable of executing queries directly
 * Result for fetch/fetchAll is the same as executing the same functions on PDO
 * with PDO::ATTR_DEFAULT_FETCH_MODE = PDO::FETCH_ASSOC
 * This class implements the parts of AbstractQueryBuilder that depends in the 
 * DataBase access library.
 */
class PDOQB extends AbstractQueryBuilder{
    
    private $identifierEscapeCharacter = '`';
    
    private $pdo;
    
    function parse($params){
        
    }
    
    function fetchAll(){
        
    }
    
    function fetch(){
        
    }

    public function quote($param) {
        return $this->pdo->quote($param);
    }

    public function quoteIdentifier($param) {
        $sc = $this->identifierEscapeCharacter;
        return $sc.str_replace($sc, $sc.$sc, $param).$sc;
    }

    
}

