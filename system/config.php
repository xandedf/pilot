<?php

/**
 * Arquivo de Config 
 *
 * @package     Pilot
 * @copyright   Copyright (c) 2013 ARG Softwares (http://www.argsoftwares.com.br) 
 * @license     Commercial
 * @author      Alexandre Gomes <alexandre.gomes@discover.com.br>
 */

define('VERSION', '1.8');
define('_NAME_', 'Pilot Framework');
define('LICENSE' , _NAME_ . ' ' . VERSION . ' - Copyright&copy ' . date('Y'));
define('__NAME__', 'pilot');
define('COMPANY', 'ARG Softwares e Consultoria');
define('APPNAME', 'APP Demo');

//Definiçoes bases do framework
define('DS', DIRECTORY_SEPARATOR);
define('APP_DIR', 'app');
define('MODULES_DIR', APP_DIR . DS . 'modules');
define('SYSTEM_DIR', 'system');
define('HELPERS_DIR', SYSTEM_DIR . DS . 'helpers');
define('WIDGETS_DIR', SYSTEM_DIR . DS . 'widgets');
define('CORE_DIR', SYSTEM_DIR . DS . 'core');
define('LIBS_DIR', 'libs');
define('DOCTRINE_DIR', LIBS_DIR . DS . 'doctrine');
define('BASE_MODELS_DIR', 'entities');
define('BASE_WEB_DIR', DS . 'web');
define('BASE_CSS_DIR', BASE_WEB_DIR . DS . 'css');
define('BASE_JS_DIR', BASE_WEB_DIR . DS . 'js');
define('BASE_IMAGES_DIR', BASE_WEB_DIR . DS . 'images');

//Definições de banco de dados
define('DB_DRIVER', 'pdo_mysql');
define('DB_SCHEMA', 'framework_sample');
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');