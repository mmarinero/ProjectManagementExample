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
    
    function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    function fetchAll(){
        return $this->pdo->query($this->sqlString)->fetchAll();
    }
    
    function fetch(){
        return $this->pdo->query($this->sqlString)->fetch();
    }

    public function quote($param) {
        return $this->pdo->quote($param);
    }

    public function quoteIdentifier($param) {
        $sc = $this->identifierEscapeCharacter;
        return $sc.str_replace($sc, $sc.$sc, $param).$sc;
    }

}
