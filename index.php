<?php
/**
 * HadesEngine
 *
 * @author  Martin Lantzsch <martin@linux-doku.de>
 */

define('HADES_DIR_ROOT', dirname(__FILE__));

// load bootstrap
require_once 'includes/bootstrap.php';

// get module from params
$module = filter::string(url::param('module'));
if (!$module)
    $module = core::setting('core', 'frontpage');

// get action from params
$action = filter::string(url::param('action'));
if (!$action)
    $action = 'main';

module::callHook('module_before', array($module, $action));

module::loadModule($module, $action);

module::callHook('module_after', array($module, $action));
