<?php

/**
 * Arquivo de Index principal 
 *
 * @package     Pilot
 * @copyright   Copyright (c) 2013 Discover (http://www.discover.com.br) 
 * @license     Commercial
 * @author      Alexandre Gomes <alexandre.gomes@discover.com.br>
 */

session_start();

header('Content-type: text/html; charset=utf-8');

if (version_compare(phpversion(), '5.2.0', '<')===true) {
    echo 'Sua versão do PHP deve ser maior do que a 5.2.0'; exit;
}

/**
 * Error reporting
 */
error_reporting(E_ALL | E_STRICT);

//Definição padrão do root padrão do Buyer Framework
define('PILOT_ROOT', getcwd());

//Inclui o arquivo de configurações padrões do Buyer Framework
$compilerConfig = PILOT_ROOT . '/system/config.php';
if (file_exists($compilerConfig)) {
    include $compilerConfig;
} else {
    throw new Exception("Arquivo {$compilerConfig} não encontrado");
    exit;
}

//Inclui o arquivo base do Buyer Framework
$compilerBase = PILOT_ROOT . DS . CORE_DIR . DS . 'base.php';
if (file_exists($compilerBase)) {
    include $compilerBase;
} else {
    throw new Exception("Arquivo {$compilerBase} não encontrado");
    exit;
}
