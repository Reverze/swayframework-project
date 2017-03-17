<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Gets current directory of this file
 */
$currentDirectory = dirname(__FILE__);

/**
 * Gets root application directory
 */
$applicationDirectory = dirname($currentDirectory);

/**
 * Includes app.controller.php which should be located in root directory
 */
$predictedPath = sprintf("%s/app/%s", $applicationDirectory, 'app.controller.php');

/**
 * Gets absolute path to app.controller
 */
$appControllerPath = realpath($predictedPath);


/**
 * If file was not found 
 */
if (!$appControllerPath){
    echoEmergency();
}

/**
 * For example, file is damaged or invalid syntax
 */
if (!@require_once ($appControllerPath)){
    echoEmergency(); 
}

/**
 * Inits framework in production mode
 */
$initController->initFrom('config.yml', 'dev');

/**
 * InitController:start triggers framework's event 'frameworkInited' which runs eg. router
 */
$initController->start();


?>