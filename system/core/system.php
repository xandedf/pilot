<?php
/**
 * Classe de configuração de sistema e parâmtros 
 *
 * @package     Pilot
 * @copyright   Copyright (c) 2013 Discover (http://www.discover.com.br) 
 * @license     Commercial
 * @author      Alexandre Gomes <alexandre.gomes@discover.com.br>
 */

class Pilot extends Pilot_Lib {
    
    private $_url;
    private $_explode;
    private $_nameAction;
    public  $_module;
    public  $_controller;
    public  $_action;
    public  $_params;
    
    public function __construct() {
        parent::__construct();
        
        $this->setUrl()->setExplode()->setModule()->setController()->setAction()->setParams();
    }
    
    private function setUrl() {
        //Recupero da url o que é modulo, controller e o que é action
        $_GET['url'] = (isset($_GET['url']) ? $_GET['url'] . DS : 'module/index/index');
        $this->_url = $_GET['url'];
        return $this;
    }
    
    private function setExplode() {
        $this->_explode = explode('/', $this->_url);
        return $this;
    }
    
    private function setModule() {
        $this->_module = $this->_explode[0];
        return $this;
    }
    
    private function setController() {
        $controller = $action = (!isset($this->_explode[1]) || $this->_explode[1] === '' || $this->_explode[1] === 'index' ? 'index' : $this->_explode[1]);
        $this->_controller = $controller;
        return $this;
    }
    
    private function setAction() {
        $action = (!isset($this->_explode[2]) || $this->_explode[2] === '' || $this->_explode[2] === 'index' ? 'index' : $this->_explode[2]);
        $this->_nameAction = $action;
        $this->_action = $action . 'Action';
        return $this;
    }
    
    private function setParams() {
        unset($this->_explode[0], $this->_explode[1], $this->_explode[2]);
        
        if (end($this->_explode) == '') {
            array_pop($this->_explode);
        }
        
        $i = 0;
        
        if (!empty($this->_explode)) {
            foreach ($this->_explode as $val) {
                if ($i % 2 != 0) {
                    $value[] = $val;
                } else {
                    $ind[] = $val;
                }
                $i++;
            }
        } else {
            $ind = array();
            $value = array();
        }
        
        if (count($ind) == count($value) && !empty($ind) && !empty($value)) {
            $this->_params = array_combine($ind, $value);
        } else {
            $this->_params = array();
        }
        
        return $this;
    }
    
    public function run() {
        
        $controllerPath = CONTROLLERS_DIR . DS . $this->_controller . 'Controller.php';
        
        if (!file_exists($controllerPath)) {
            throw new Exception("Arquivo {$controllerPath} não encontrado");
        }
        
        require_once $controllerPath;
        $controller = $this->_controller . 'Controller';
        
        $app = new $controller($this->_module, $this->_controller, $this->_params, $this->_nameAction);
        
        if (!method_exists($app, $this->_action)) {
            throw new Exception("A action {$this->_action} não existe");
        }
        
        $app->init();
        $action = $this->_action;
        $app->$action();
        
    }
    
}