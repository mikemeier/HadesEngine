<?php
/**
 * HadesEngine
 *
 * @author  Martin Lantzsch <martin@linux-doku.de>
 */

// load bootstrap
require_once 'includes/bootstrap.php';

// get page from params
$page = filter::string($url->param('page'));
if(!$page)
    $page = utils::setting('core', 'frontpage');
// get action from params
$action = filter::string($url->param('action'));
if(!$action)
    $action = 'main';
// module main class
require_once 'modules/'.$page.'/'.$page.'.php';
// call function
call_user_func_array(array($page, $action), array());