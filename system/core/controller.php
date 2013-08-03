<?php
/**
 * Classe padrão de controller
 *
 * @package     Pilot
 * @copyright   Copyright (c) 2013 Discover (http://www.discover.com.br) 
 * @license     Commercial
 * @author      Alexandre Gomes <alexandre.gomes@discover.com.br>
 */

class Pilot_Core_Controller extends Pilot_Lib {
    
    private $module;
    private $controller;
    private $params;
    private $action;

    private $title;
    private $templatePath;
    private $footer;
    private $css = array();
    private $csslibs = array();
    private $js = array();
    private $jslibs = array();


    public function __construct($module, $controller, $params, $action) {
        $this->module = $module;
        $this->controller = $controller;
        $this->params = $params;
        $this->action = $action;
    }
    
    public function init() {
        
    }

    protected function getModule() {
        return $this->module;
    }
    
    protected function getController() {
        return $this->controller;
    }
    
    protected function getParams($key = null) {
        if (!is_null($key)) {
            return $this->params[$key];
        }
        return $this->params;
    }
    
    protected function getAction() {
        return $this->action;
    }

    protected function setTitle($value) {
        $this->title = $value;
        return $this;
    }
    
    protected function getTitle() {
        return $this->title;
    }

    protected function getTemplate($vars = null) {
        
        if (is_array($vars) && count($vars) > 0) {
            extract($vars, EXTR_PREFIX_ALL, '_');
        }
        
        return require_once $this->templatePath;
    }

    protected function setFooter($value) {
        $this->footer = $value;
        return $this;
    }

    protected function setHeader($value) {
        $this->header = $value;
        return $this;
    }

    protected function getFooter($vars = null) {
        
        if (empty($this->footer)) {
            throw new Exception(self::MSG_ERRO_EXISTE_FOOTER, self::MSG_ERRO_EXISTE_FOOTER_COD);
        }
        
        return $this->getTemplateFile($this->footer, $vars);
    }
    
    protected function getHeader($vars = null) {
        
        if (empty($this->header)) {
            throw new Exception(self::MSG_ERRO_EXISTE_HEADER, self::MSG_ERRO_EXISTE_HEADER_COD);
        }
        
        return $this->getTemplateFile($this->header, $vars);
    }
    
    protected function getTemplateFile($name, $vars) {
        
        if (is_array($vars) && count($vars) > 0) {
            extract($vars, EXTR_PREFIX_ALL, '_');
        }
        
        $templatePath = MODULES_DIR . DS . $this->module . DS . 'views' . DS . $name . 'Template.phtml';
        $templateFile = PILOT_ROOT . DS . $templatePath;
        
        if (file_exists($templateFile)) {
            return include $templateFile;
        } else {
            $msg = self::___(self::MSG_ERRO_FILE_EXISTE, $templateFile);
            throw new Exception($msg);
        }
    }


    protected function setCSS($value) {
        $this->css[] = $value;
    }
    
    protected function setCSSLibs($value) {
        $this->csslibs[] = $value;
    }

    protected function getCSS($useModule = true) {
        
        $base = BASE_CSS_DIR;
        if ($useModule) {
             $base .= DS . $this->module;
        }
        foreach ($this->css as $value) {
            echo "<link rel=stylesheet type=text/css href={$base}/{$value} />";
        }
    }
    
    protected function getCSSLibs() {
        $base = DS . LIBS_DIR . DS;
        foreach ($this->csslibs as $value) {
            echo "<link rel=stylesheet type=text/css href={$base}{$value} />";
        }
    }
    
    protected function setJS($value) {
        $this->js[] = $value;
    }

    protected function setJSLibs($value) {
        $this->jslibs[] = $value;
    }
    
    protected function getJS() {
        $base = BASE_JS_DIR;
        foreach ($this->js as $value) {
            echo "<script src={$base}/{$value}></script>";
        } 
    }

    protected function getJSLibs() {
        $base = DS . LIBS_DIR . DS;
        foreach ($this->jslibs as $value) {
            echo "<script src={$base}{$value}></script>";
        } 
    }
    
    protected function loadTemplate($name, $vars = null) {
        
        $baseTemplatePath = PILOT_ROOT . DS . MODULES_DIR . DS . $this->module . DS . 'views' . DS . 'baseTemplate.phtml';
        
        if (!file_exists($baseTemplatePath)) {
            $baseTemplatePath = CORE_DIR . DS . 'baseTemplate.phtml';
        }
        
        $templatePath = MODULES_DIR . DS . $this->module . DS . 'views' . DS . $name . 'Template.phtml';
        $this->templatePath = $templatePath;
        $templateFile = PILOT_ROOT . DS . $templatePath;
        
        if (file_exists($templateFile)) {
            require_once $baseTemplatePath;
        } else {
            $msg = self::___(self::MSG_ERRO_FILE_EXISTE, $templateFile);
            throw new Exception($msg);
        }
        exit();
    }
    
}