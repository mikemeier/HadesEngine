<?php
/**
 * Load all needed files and initialize the module system
 *
 * @author  Martin Lantzsch <martin@linux-doku.de>
 */

// start session
session_start();

// include required files
require_once 'autoloader.php';
require_once 'functions.php';

// load settings
core::loadSettings();

// define install constant
define('NR', core::setting('core', 'install_number'));

// connect to database
core::connectDB(core::setting('core', 'db_host'),
                core::setting('core', 'db_user'),
                core::setting('core', 'db_password'),
                core::setting('core', 'db_database'));

// process params
url::init($_GET['p']);

// load langauge
lang::init(core::setting('core', 'lang'));

// set page title
tpl::title(core::setting('core', 'site_name'));

// init module system
module::init();
