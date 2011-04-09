<?php
/**
 * Load all needed files and initialize the module system
 *
 * @author  Martin Lantzsch <martin@linux-doku.de>
 */

// start session
session_start();

// load classAutoLoader
require_once 'classAutoLoader.php';

// load settings
utils::loadSettings();

// define install constant
define('NR', utils::setting('core', 'install_number'));

// connect to database
core::connectDB(utils::setting('core', 'db_host'),
                utils::setting('core', 'db_user'),
                utils::setting('core', 'db_password'),
                utils::setting('core', 'db_database'));

// process params
url::init($_GET['p']);

// load langauge
lang::init(utils::setting('core', 'lang'));

// set page title
tpl::title(utils::setting('core', 'site_name'));


// get page from params
$module = filter::string(url::param('module'));
if(!$module)
    $module = utils::setting('core', 'frontpage');

// get action from params
$action = filter::string(url::param('action'));
if(!$action)
    $action = 'main';

// module main class
$moduleFile = 'modules/'.$module.'/'.$module.'.php';

// load module if exists
if(file_exists($moduleFile))
    require_once $moduleFile;
else
    throw new Exception('Bootstrap: Failed loading module {'.$module.'}');

// check if action method exists
if(!method_exists($module, $action))
    $action = 'main';

// call function
call_user_func_array(array($module, $action), url::paramsAsArray());