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
        $pdo = new PDO($database, $user, $password, self::$attrs);
        return $pdo;
    }
    
    static function getExtendedPDO(){
        static $pdo = null;
        if (!is_null($pdo)) {
            return $pdo;
        }
        list($database, $user,$password) = self::getAccessData();
        $pdo = new ExtendedPDO($database, $user, $password, self::$attrs);
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

