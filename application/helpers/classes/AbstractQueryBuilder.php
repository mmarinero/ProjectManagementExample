<?php

/**
 * PHP query builder 
 */
Abstract class AbstractQueryBuilder {

    protected $idField = 'id';

    protected $sqlString = null;
    
    function get($table, $where = array(), $fields = array(), $limit = null, $offset = null){
	$selectClause = is_empty($fields) ? '*' : $this->parseList($fields,'quoteIdentifier');
        
	$this->sqlString = "select $selectClause from $this->quoteIdentifier($table) ". 
	"where {$this->parseIdValList($where)}"; 
	return $this;
    }

    private function parseIdValList($list, $defaultOperator = '='){
	if (is_array($list)){
	    $clauses = array();
	    foreach($list as $id -> $value){
		if (is_string($id)){
		    $clauses[$id] = "{$this->quoteIdentifier($id)} $defaultOperator {$this->quote($value)}";
		} elseif (is_int) {
		    $clauses[$id] = $value; 
		} elseif (is_array($value)) {
		    $clauses[$id] = $this->quoteIdentifier($id).' '.$value['operator'].' '.$this->quote($value['value']);
		} else {
		    throw new Exception('The list has an invalid element: '.var_export($params, true));
		}
	    }
	    return implode(', ',$clauses);
	} elseif (is_int($list)) {
	    return "{$this->quote($this->idField)} = {$this->quote($list)}";
	} else {
	    return $list;
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
        $this->sqlString = "update {$this->quoteIdentifier($table)} set {$this->parseIdValList($fields)} where ".
	    $this->parseIdVallist($where);
	return $this;
    }
    
    function delete($table, $where){
        $this->sqlString = "delete from {$this->quoteIdentifier($table)} where {$this->parseIdValList($where)}"; 
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
        return $this->sqlString;
    }
    
    function parse($params){
        throw new Exception('Not yet implemented'.  var_export($params, true));
    }
    
    abstract function quote($param);
    
    abstract function quoteIdentifier($param);
}