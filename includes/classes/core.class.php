<?php
/**
 * The core class provides basic functionality and all globally available objects
 *
 * @author  Christian Neff <christian.neff@gmail.com>
 */
class core {

    /**
     * The database class object
     * @var     object
     * @access  public
     * @static
     */
    public static $db;

    /**
     * All loaded settings
     * @var     array
     * @access  private
     * @static
     */
    private static $_settings = array();

    /**
     * Load settings from file into our array
     * @return  void
     * @access  public
     * @static
     */
    public static function loadSettings() {
        self::$_settings = parse_ini_file('includes/settings.ini.php', true);
    }

    /**
     * Get the value of a specific setting
     * @param   string  $section  From this section...
     * @param   string  $name     ... grab this setting
     * @return  mixed
     * @access  public
     * @static
     */
    public static function setting($section, $name) {
        return self::$_settings[$section][$name];
    }

    /**
     * Get the current module, action or theme
     * @param   string  $aspect  Which aspect do you want to know? Possible values: 'module', 'action', 'theme'
     * @global  string  $page
     * @return  string
     * @access  public
     * @static
     */
    public static function current($aspect) {
        if ($aspect == 'module') {
            if (url::param('page')) {
                return url::param('page');
            } else {
                return module::$module;
            }
        } elseif ($aspect == 'action') {
            if (url::param('action')) {
                return url::param('action');
            } else {
                return 'main';
            }
        } elseif ($aspect == 'theme') {
            return self::setting('core', 'theme');
        }
    }

    /**
     * Connect to the database
     * @param   string   $host      The hostname, mostly 'localhost'
     * @param   string   $user      The username to authenticate
     * @param   string   $password  The password to authenticate
     * @param   string   $database  The name of the database
     * @return  void
     * @access  public
     * @static
     */
    public static function connectDB($host, $user, $password, $database) {
        if (!self::$db instanceof database)
            self::$db = new database($host, $user, $password, $database);
    }

}
