<?php

/**
 * Database related classes factory
 * Shortcuts used to facilitate direct use and avoid the need for shortcuts
 * to use it.
 * Longname DataBaseFactory
 */
final class DB {
    
    private static $attrs = array(PDO::ATTR_PERSISTENT => True,
        PDO::ATTR_EMULATE_PREPARES=> True,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
    
    /**
     * returns an instance of the PDO class with custom parameters
     * Error mode is exceptions.
     * Default fetch mode is associatibe array only with no numeric indexes.
     * Emulate prepares is set to true.
     * @staticvar PDO $pdo shared PDO instance
     * @param boolean $shared Share the instance as in a singleton or create a private
     * instance for this call.
     * @return PDO 
     */
    static function pdo($shared = true){
        static $pdo = null;
        if ($pdo !== null && $shared) {
            return $pdo;
        }
        list($database, $user, $password) = self::getAccessData();
        try {
            $newPdo = new PDO($database, $user, $password, self::$attrs);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit();
        }
        if ($shared){
            $pdo = $newPdo;
        }
        return $newPdo;
    }

    private static function getAccessData(){
        $ci=& get_instance();
        $ci->config->load('database');
        return array("{$ci->config->item('hostname')};dbname={$ci->config->item('database')}", 
                $ci->config->item('username'),
                $ci->config->item('password'));
    }
    
    /**
     * returns an instance of the QueryBuilder class
     * @staticvar null $qb
     * @param type $shared Share the instance as in a singleton or create a private
     * instance for this call.
     * @param type $pdo PDO instance to use for que QueryBuilder database facilities
     * @return PDOQB
     */
    static function qb($shared = true, $pdo = null){
        static $qb = null;
        if ($qb !== null && $shared) {
            return $pdo;
        }
        $constructorPdo = $pdo !== null ? $pdo : self::pdo($shared);
        $newQb = new PDOQB($constructorPdo);
        if ($shared){
            $qb = $newQb;
        }
        return $newQb;
    }

}