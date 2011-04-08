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
$url = new url($_GET['p']);

// load langauge
lang::init(utils::setting('core', 'lang'));

// set page title
tpl::title(utils::setting('core', 'site_name'));
