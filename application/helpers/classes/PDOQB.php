<?php
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
    
    private $result = null;
    
    function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    function fetchAll($fetch_style = null){
        if (is_null($fetch_style)){
            $fetch = $this->pdo->query($this->sql())->fetchAll();
        } else {
            $fetch = $this->pdo->query($this->sql())->fetchAll($fetch_style);
        }
        return $fetch;
    }
    
    function fetch($fetch_style = null){
        if ($this->result === null){
            $this->result = $this->pdo->query($this->sql());
        }
        if (is_null($fetch_style)){
            $fetch = $this->pdo->query($this->sql())->fetch();
        } else {
            $fetch = $this->pdo->query($this->sql())->fetch($fetch_style);
        }
        return $fetch;
    }
    
    function exec(){
        return $this->pdo->exec($this->sql());
    }

    public function quote($param) {
        return $this->pdo->quote($param);
    }

    public function quoteIdentifier($param) {
        $sc = $this->identifierEscapeCharacter;
        return $sc.str_replace($sc, $sc.$sc, $param).$sc;
    }

}
