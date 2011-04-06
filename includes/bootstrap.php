<?php
/**
 * Load all needed files and init module system
 * @author  Martin Lantzsch <martin@linux-doku.de>
 */

// start session
session_start();

// load classAutoLoader
require_once 'classAutoLoader.php';

// load settings
utils::loadSettings();

// connect to database
db::connect(utils::setting('core', 'db_host'),
            utils::setting('core', 'db_user'),
            utils::setting('core', 'db_password'),
            utils::setting('core', 'db_database'));

// define install constant
define('NR', utils::setting('core', 'install_number'));

// progress params
$url = new url($_GET['p']);

// set page title
tpl::title(utils::setting('core', 'site_name'));