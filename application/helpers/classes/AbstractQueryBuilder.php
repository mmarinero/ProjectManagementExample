<?php

/**
 * PHP query builder 
 */
Abstract class AbstractQueryBuilder {

    private $idField = 'id';

    private $sqlString = null;
    
    function get($table, $where = array(), $fields = array(), $limit = null, $offset = null){
	$selectClause = is_empty($fields) ? '*' : $this->parseList($fields,'quoteIdentifier');
	$this->sqlString = "select $selectClause from $this->quoteIdentifier($table) ". 
	"where $this->parseIdValList($where)"; 
	return $this;
    }

    function getId($table, $id, $fields) { 
	
    }
	

    private function parseIdValList($list, $defaultOperator = '='){
	if (is_array($where)){
	    $clauses = array();
	    foreach($where as $id -> $value){
		if (is_string($id)){
		    $clauses[$id] = "$this->quoteIdentifier($id) $defaultOperator $this->quote($value)";
		} elseif (is_int) {
		    $clauses[$id] = $value; 
		} elseif (is_array($value)) {
		    $clauses[$id] = "$this->quoteIdentifier($id) $value['operator'] $this->quote($value['value'])";
		} else {
		    //TODO output list
		    throw new Exception("The list has an invalid element")
		}
	    }
	    return implode(', ',$clauses);
	} else (is_int($where)) {
	    return "$this->quote($this->idField) = $this->quote($where)";
	} else {
	    return $where;
	}
    }
    
    private function parseList($list, $quoteFunction) {
	if (is_array($list)){
	    return implode(', ',array_map(array($this,$quoteFunction),$list));
	} else {
	    return $list;
	}
    }
    
    function insert($table, $fields){
	$clause = '('.$this->parseList(array_keys($fields), 'quoteIdentifier').') '.$this->parseList('quote');
	$this->sqlString = "insert into $this->quoteIdentifier($table) $clause";  
	return $this;
    }

    function update($table, $fields, $where){
        $this->sqlString = "update $this->quoteIdentifier($table) set $this->parseIdValList($fields) where ".
	    "$this->parseIdVallist($where)";
	return $this;
    }
    
    function delete($table, $where){
        $this->sqlString = "delete from $this->quoteIdentifier($table) where $this->parseIdValList($where)"; 
	return $this;
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

