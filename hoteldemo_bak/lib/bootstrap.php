<?php
//read the config file, set some additional values, and store it all in the registry                    
require_once 'Zend/Config/Ini.php';
require_once 'Zend/Registry.php';

$config = new Zend_Config_Ini($sitePath . '/config.ini', NULL, TRUE);
$config->general->mode = $pathArr[4];
$config->general->siteName = $pathArr[5]; 
$config->general->sitePath = $sitePath;
$config->general->basePath = $basePath;
$config->general->modulePath = $basePath . '/modules';

require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->setFallbackAutoloader(true);
$loader->suppressNotFoundWarnings(false);

Zend_Registry::set('config', $config);

//set the timezone
date_default_timezone_set($config->general->timezone);

$db_to_use = 'cmmsisort';

/*instantiate the db connection*/
require_once 'Zend/Db.php';
$db = Zend_Db::factory('PDO_MYSQL', array(
	'dbname'=>$db_to_use,
	'username'=>'cmmsisort',
	'password'=>'NwCF6GxMFECcn7aF',
	'host'	=> 'localhost',
	'port'=>'3306'
));
Zend_Registry::set('db', $db);

/* FOR ACTION PLAN */
$db2 = Zend_Db::factory('PDO_MYSQL', array(
	'dbname'=>$db_to_use,
	'username'=>'cmmsisort',
	'password'=>'NwCF6GxMFECcn7aF',
	'host'	=> 'localhost',
	'port'=>'3306'
));
Zend_Registry::set('db2', $db2);

/* FOR COMMENTS */
$db3 = Zend_Db::factory('PDO_MYSQL', array(
	'dbname'=>$db_to_use,
	'username'=>'cmmsisort',
	'password'=>'NwCF6GxMFECcn7aF',
	'host'	=> 'localhost',
	'port'=>'3306'
));
Zend_Registry::set('db3', $db3);


//Instantiate session handler
require_once 'Zend/Session.php';
require_once 'sessionSaveHandler.php';
$sessionOptions = $config->session->toArray();
$sessionOptions['cookie_domain']=$config->general->domain;
Zend_Session::setOptions($sessionOptions);
Zend_Session::setSaveHandler(new falconSessionSaveHandler($db, $config));
Zend_Session::start();

//Instantiate Auth Object.
require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Storage/Session.php';
$auth = Zend_Auth::getInstance(); 
$auth->setStorage(new Zend_Auth_Storage_Session('Falcon_Auth'));
Zend_Registry::set('auth', $auth);
        
//Instantiate Database Logger
require_once 'Zend/Log.php';                
require_once 'Zend/Log/Writer/Db.php';
require_once 'Zend/Log/Writer/Mock.php';   
$columnMapping = array('level' => 'priority', 'message' => 'message', 'site_id'=>'site_id', 'type'=>'type', 'code'=>'code', 'location'=>'location', 'trace'=>'trace', 'request_uri'=>'request_uri', 'remote_addr'=>'remote_addr','referer'=>'referer');
$writer = new Zend_Log_Writer_Db($db, 'system_log', $columnMapping);
$dbLogger = new Zend_Log($writer);
Zend_Registry::set('dbLogger', $dbLogger);


//Initialize the front controller and set BaseURL
require_once 'Zend/Controller/Front.php';
$front = Zend_Controller_Front::getInstance();

//use standard Zend Framework module layout. Assumes all controllers are stored in /modulename/controllers
$front->addModuleDirectory($basePath . '/modules');

//Exception handling
if ($config->general->debug==TRUE)
{
    
    $front->throwExceptions(TRUE);
}
else
{
    ini_set('display_errors', 0);
    require_once 'Zend/Controller/Plugin/ErrorHandler.php';
    $front->throwExceptions(FALSE);
    $errPlugin = new Zend_Controller_Plugin_ErrorHandler();
    $errPlugin ->setErrorHandlerModule("default")
    			->setErrorHandlerController('error')       
               ->setErrorHandlerAction('error');
    $front->registerPlugin($errPlugin);  
}


//set parameters that should be available to the Action controller
$front->setParam('noViewRenderer', true);

$front->dispatch();
?>
