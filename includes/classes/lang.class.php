<?php
/**
 * Handling of different languages and caching
 *
 * @author  Martin Lantzsch <martin@linux-doku.de>
 */
class lang {
    private static $lang;
    private static $strings = array();

    /**
     * Init lang system and load all lang strings (of cache or modules)
     * @param   string  $lang
     */
    public static function init($lang) {
        self::$lang = $lang;
        // check if cache file exists
        $langFile = 'includes/cache/lang_'.$lang.'.ini.php';
        if(file_exists($langFile) && utils::setting('core', 'lang_cache')) {
            // load lang cache file
            self::$strings = parse_ini_file($langFile);
        } else {
            // scan module dirs
            $moduleDir = scandir('modules/');
            // remove dots
            unset($moduleDir[0]);
            unset($moduleDir[1]);
            // go through all dirs
            foreach($moduleDir as $name) {
                self::add($name);
            }
            // write to cache if enabled
            if(utils::setting('core', 'lang_cache'))
                lang::writeCache();
        }
    }

    /**
     * Add the lang file of a module
     * @param   string  $module
     */
    public static function add($module) {
        $langFile = 'modules/'.$module.'/lang/'.self::$lang.'.lang.php';
        // if file exists add it
        if(file_exists($langFile))
            self::addFile($langFile);
    }

    /**
     * Add a lang file from a path (relative path!)
     * @param   string  $file
     */
    public static function addFile($file) {
        self::$strings = parse_ini_file($file);
    }

    /**
     * write current lang strings to cache
     */
    public static function writeCache() {
        return utils::writeArrayToIni('includes/cache/lang_'.self::$lang.'.ini.php', self::$strings);
    }

    /**
     * get a lang string
     * @param   string  $string
     * @return  string
     */
    public static function get($string) {
        return self::$strings[$string];
    }
}