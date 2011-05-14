<?php
/**
 * Autoloader for classes
 *
 * @author  Martin Lantzsch <martin@linux-doku.de>
 */

function autoload($className) {
    global $module;
    $globalClass = '../includes/classes/'.$className.'.class.php';
    $adminClass = 'includes/classes/'.$className.'.class.php';
    $moduleClass = 'modules/'.$module.'/classes/'.$className.'.class.php';
    if (file_exists($globalClass)) {
        require_once $globalClass;
    } elseif (file_exists($adminClass)) {
        require_once $adminClass;
    } elseif (file_exists($moduleClass)) {
        require_once $moduleClass;
    } else {
        throw new Exception('autoload: Class \''.$className.'\' not found!');
    }
}

// register autoloader
spl_autoload_register(null, false);
spl_autoload_register('autoload');
