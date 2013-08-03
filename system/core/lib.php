<?php
/**
 * Classe de biblioteca geral do Pilot
 *
 * @package     Pilot
 * @copyright   Copyright (c) 2013 Discover (http://www.discover.com.br) 
 * @license     Commercial
 * @author      Alexandre Gomes <alexandre.gomes@discover.com.br>
 */

class Pilot_Lib extends Pilot_Const {
    
    function __construct() {
        
    }
    
    /**
     * Método que grava log
     * 
     * @param type $message
     * @param type $logType
     * @return boolean|\buyer_lib
     */
    public static function log($message, $logType, $exception = false) {
        
        try {
            
            $data = date("d-m-y");
            $hora = date("H:i:s");
            $ip = $_SERVER['REMOTE_ADDR'];

            switch ($logType) {
               case 1:
                     $type = "[ERROR]";
                     break;
               case 2:
                     $type = "[WARNING]";
                     break;
               case 3:
                     $type = "[INFORMATION]";
                     break;
            }         
            
            //Path do arquivo
            $logDir = realpath(PILOT_ROOT) . DS . "var";

            if (!is_dir($logDir)) {
                mkdir($logDir);
                chmod($logDir, 0777);
            }
            $logDir = $logDir . DS . 'log';
            if (!is_dir($logDir)) {
                mkdir($logDir);
                chmod($logDir, 0777);
            }
            
            if ($exception) {
                $logFile = __NAME__."_exception.log";
            } else {
                $logFile = __NAME__.".log";
            }

            $arquivo = $logDir . DS . $logFile;
            
            if (!file_exists($arquivo)) {
                file_put_contents($arquivo, '');
                chmod($arquivo, 0777);
            }       
            
            //Texto a ser impresso no log:
            $texto = $type ."[$data][$hora][$ip]-> ".$message."\n";

            file_put_contents($arquivo, $texto, FILE_APPEND);
            return true;
            
        } catch (Exception $exc) {
            throw $exc;
        }
        
    }
    
    /**
     * Método que grava log exception
     * 
     * @param type $message
     * @return boolean|\buyer_lib
     */
    public static function logException($message) {
        return self::log($message, self::LOG_ERROR, true);
    }
    
    /**
     * Método mágico de tradução de msg
     *
     */
    public static function ___() {
        
        $args = func_get_args();
        $var  = array_shift($args);
        $s    = vsprintf($var, $args);
        
        return $s;
        
    }
}
