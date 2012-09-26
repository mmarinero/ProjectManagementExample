<?php

final class PDOFactory {
    
    private static $attrs = array(PDO::ATTR_PERSISTENT => True,
        PDO::ATTR_EMULATE_PREPARES=> True,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
    
        
    static function getCustomPDO($new = false){
        return self::getGenericPDO('PDO', $new);
    }
    
    static function getExtendedPDO($new = false){
        return self::getGenericPDO('ExtendedPDO', $new);
    }
    
    private static function getGenericPDO($pdoClass, $new = false){
        static $pdo = null;
        if (!is_null($pdo) && !$new) {
            return $pdo;
        }
        list($database, $user, $password) = self::getAccessData();
        try {
            $newPdo = new $pdoClass($database, $user, $password, self::$attrs);
        } catch (PDOException $e){
            echo $e->getMessage();
            exit();
        }
        if ($pdo === null){
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

}