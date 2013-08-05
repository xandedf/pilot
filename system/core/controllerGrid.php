<?php

/**
 * Classe padrão de controller com Grid
 *
 * @package     Pilot
 * @copyright   Copyright (c) 2013 Discover (http://www.discover.com.br) 
 * @license     Commercial
 * @author      Alexandre Gomes <alexandre.gomes@discover.com.br>
 */

class controllerGrid extends Pilot_Core_Controller {
    
    public function init() {
        parent::init();
        $this->setJSLibs('bootstrap/js/jquery.dataTables.min.js');
    }
    
}