<?php

/**
 * some good and useful things ;-)
 *
 * @author  Martin Lantzsch <martin@linux-doku.de>
 */
class utils {

    private static $settings = array();

    /**
     * load settings from file to array
     */
    public static function loadSettings() {
        self::$settings = parse_ini_file('includes/settings.php', true);
    }

    /**
     * Give value of this setting
     * @param   string  $section
     * @param   string  $name
     * @return  mixed
     */
    public static function setting($section, $name) {
        return self::$settings[$section][$name];
    }

    /**
     * Get current
     * - module
     * - action
     * - theme
     * @global  string  $page
     * @param   string  $name
     * @return  string
     */
    public static function current($name) {
        if($name == 'module') {
            if(url::param('page')) {
                return url::param('page');
            } else {
                global $page;
                return $page;
            }
        } elseif($name == 'action') {
            if(url::param('action'))
                return url::param('action');
            else
                return 'main';
        } elseif($name == 'theme') {
            return self::setting('core', 'theme');
        }
    }

    /**
     * Write given array to ini file
     *
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