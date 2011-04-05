<?php
/**
 * Auto Load Classes
 *
 * @author  Martin Lantzsch <martin@linux-doku.de>
 */

function load($name) {
    $path = 'includes/classes/'.$name.'.class.php';
    if(file_exists($path))
        require_once $path;
    else
        throw new Exception('classAutoLoader: Class "'.$path.'" not found!');
}

// register autploader
spl_autoload_register(null, false);
spl_autoload_register('load');