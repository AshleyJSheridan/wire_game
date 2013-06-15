<?php
/**
 * Index Loader
 * 
 * @package TMW WIRE GAME
 * @version 1.0.0
 */
ini_set('display_errors', 1);

// Define path to application directory to always use the 
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

defined('RESOURCE_PATH') || define('RESOURCE_PATH', realpath(dirname(__FILE__) . '/../public_html'));

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library/'),
    realpath(APPLICATION_PATH . '/models/'),
    get_include_path(),
)));

/**
 * Require Zend Files 
 * Zend_Application
 */
require_once 'Zend/Application.php';
require_once 'Zend/Loader/Autoloader.php';

$loader = Zend_Loader_Autoloader::getInstance();

$loader->registerNamespace('TMW_');
$loader->registerNamespace('ADMIN_');

Zend_Session::start();

// Create application, bootstrap, and run
$application = new Zend_Application(
        APPLICATION_ENV,
        APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap()->run();