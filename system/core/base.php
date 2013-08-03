<?php
/**
 * Arquivo de base para o frameword
 *
 * @package     Pilot
 * @copyright   Copyright (c) 2013 ARG Softwares (http://www.argsoftwares.com.br) 
 * @license     Commercial
 * @author      Alexandre Gomes <alexandre.gomes@discover.com.br>
 */

require_once CORE_DIR . DS . 'const.php';
require_once CORE_DIR . DS . 'lib.php';
require_once CORE_DIR . DS . 'system.php';
require_once CORE_DIR . DS . 'controller.php';
require_once CORE_DIR . DS . 'model.php';

$appPilot = new Pilot();

$module = $appPilot->_module;

//Defino os diretórios dos controllers para o módulo específico da aplicação
define('MODELS_DIR', MODULES_DIR . DS . $module . DS . 'models');
define('CONTROLLERS_DIR', MODULES_DIR . DS . $module . DS . 'controllers');
define('VIEWS_DIR', MODULES_DIR . DS . $module . DS . 'views');

//inclui os autoloads para os helpers, models e entities
$loader = require LIBS_DIR . DS . 'autoload.php';
$loader->add('', PILOT_ROOT . DS . CORE_DIR);
$loader->add('', PILOT_ROOT . DS . LIBS_DIR);
$loader->add('', PILOT_ROOT . DS . BASE_MODELS_DIR);
$loader->add('', PILOT_ROOT . DS . MODELS_DIR);
$loader->add('', PILOT_ROOT . DS . HELPERS_DIR);
$loader->add('', PILOT_ROOT . DS . WIDGETS_DIR);
$loader->add('', BASE_CSS_DIR);
$loader->add('', BASE_IMAGES_DIR);
$loader->add('', BASE_JS_DIR);

//starto o sistema
try {
    $appPilot->run();
} catch (Exception $exc) {
    Pilot::logException($exc->getMessage());
    echo $exc->getMessage();
}

