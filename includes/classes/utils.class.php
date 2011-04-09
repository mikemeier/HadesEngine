<?php
/**
 * This class provides some good and useful things. ;-)
 *
 * @author  Martin Lantzsch <martin@linux-doku.de>
 */
class utils {

    private static $_settings = array();

    /**
     * Load settings from file into our array
     * @return  void
     */
    public static function loadSettings() {
        self::$_settings = parse_ini_file('includes/settings.php', true);
    }

    /**
     * Get the value of a specific setting
     * @param   string  $section  From this section...
     * @param   string  $name     ... grab this setting
     * @return  mixed
     */
    public static function setting($section, $name) {
        return self::$_settings[$section][$name];
    }

    /**
     * Get the current module, action or theme
     * @param   string  $aspect  Which aspect do you want to know? Possible values: 'module', 'action', 'theme'
     * @global  string  $page
     * @return  string
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
        } elseif($aspect == 'theme') {
            return self::setting('core', 'theme');
        }
    }

    /**
     * Generates a URL from given module, action and arguments
     * @param   string  $module  The module where the link goes to. If NULL the current module is used.
     * @param   string  $action  The action where the link goes to. If NULL the current action is used.
     * @param   array   $args    The arguments to use, optional.
     * @return  string
     */
    function makeURL($module = null, $action = null, $args = null) {
        $url = '?p=';
        if (is_string($module)) {
            $url .= $module;
        } else {
            $url .= self::current('module');
        }
        if (is_string($action)) {
            $url .= '/'.$action;
        } else {
            $url .= '/'.self::current('action');
        }
        if (is_array($args)) {
            foreach ($args as $arg) {
                $url .= '/'.$arg;
            }
        }
        return $url;
    }

    /**
     * Write given array to ini file
     * @param   string   $file
     * @return  array    $content
     */
    public static function writeArrayToIni($file, $content) {
        if(is_writeable($file) == true || !file_exists($file)) {
            $c = ";<?php die() ?>\n";
            foreach($content as $item1 => $item2) {
                if(is_array($item2)) {
                    $c = $c.'['.$item1."]\n";
                    foreach($item2 as $item3 => $item4) {
                        $c = $c.$item3.' = '.$item4."\n";
                    }
                } else {
                    $c = $c.$item1.' = '.$item2."\n";
                }
            }
            return file_put_contents($file, $c);
        }
    }

}
