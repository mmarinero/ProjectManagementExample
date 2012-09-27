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
    
    private $result = null;
    
    function __construct() {
        $this->link = SYS::_db()->connect();
    }
    
    function fetchAll(){
        if (!$rs = mysqli_query($this->_link,$this->sql())){
            throw new DataBaseException('Query Failed: ' . mysqli_error($this->_link) . '(' . mysqli_errno($this->_link) . ')');
        }
        return mysqli_fetch_all($rs, MYSQLI_ASSOC);
    }
    
    function fetch(){
        if ($this->result === null){
            if (!$this->result = mysqli_query($this->_link,$this->sql())){
                throw new DataBaseException('Query Failed: ' . mysqli_error($this->_link) . '(' . mysqli_errno($this->_link) . ')');
            }
        }
        return mysqli_fetch_assoc($this->result);
    }
    
    function exec(){
        return mysqli_num_rows(mysqli_query($this->_link,$this->sql()));
    }

    public function quote($param) {
        return "'".mysqli_real_escape_string($this->link, $param)."'";
    }

    public function quoteIdentifier($param) {
        return '`'.str_replace(array('`'),array('``'), $param).'`';
    }
}
