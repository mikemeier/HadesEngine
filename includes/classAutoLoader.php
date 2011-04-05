<?php
/**
 * Auto Load Classes
 *
 * @author  Martin Lantzsch <martin@linux-doku.de>
 */

function load($name) {
    $path = 'includes/classes/'.$name.'.class.php';
    $moduleClassPath = 'modules/'.utils::current('module').'/classes/'.$name.'.class.php';
    if(file_exists($path))
        require_once $path;
    elseif(file_exists($moduleClassPath))
        require_once $moduleClassPath;
    else
        throw new Exception('classAutoLoader: Class "'.$path.'" or "'.$moduleClassPath.'" not found!');
}

// register autoloader
spl_autoload_register(null, false);
spl_autoload_register('load');