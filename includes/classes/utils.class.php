<?php
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

    public static function current($name) {
        if($name == 'module') {
            return url::param('page');
        } elseif($name == 'action') {
            if(url::param('action'))
                return url::param('action');
            else
                return 'main';
        }
    }
}