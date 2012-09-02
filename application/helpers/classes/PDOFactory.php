<?php

final class PDOFactory {
    
    private static $attrs = array(PDO::ATTR_PERSISTENT => True,
        PDO::ATTR_EMULATE_PREPARES=> True,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
    
    static function getCustomPDO(){
        static $pdo = null;
        if (!is_null($pdo)) {
            return $pdo;
        }
        list($database, $user,$password) = self::getAccessData();
        try {
            $pdo = new PDO($database, $user, $password, self::$attrs);
        } catch (PDOException $e){
            echo $e->getMessage();
            exit();
        }
        return $pdo;
    }
    
    static function getExtendedPDO(){
        throw new Exception('Not yet implemented'.  var_export($params, true));
        static $pdo = null;
        if (!is_null($pdo)) {
            return $pdo;
        }
        list($database, $user,$password) = self::getAccessData();
        try {
            $pdo = new ExtendedPDO($database, $user, $password, self::$attrs);
        } catch (PDOException $e){
            echo $e->getMessage();
            exit();
        }
        return $pdo;
    }
    
    
    private static function getAccessData(){
        $ci=& get_instance();
        $ci->config->load('database');
        return array("{$ci->config->item('hostname')};dbname={$ci->config->item('database')}", 
                $ci->config->item('username'),
                $ci->config->item('password'));
    }
    
}

