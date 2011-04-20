<?php
/**
 * Handling of different languages (with caching)
 *
 * @author  Martin Lantzsch <martin@linux-doku.de>
 */
class lang {

    /**
     * The currently selected language
     * @var     string
     * @access  private
     * @static
     */
    private static $_lang;

    /**
     * All registered strings with their translation
     * @var     array
     * @access  private
     * @static
     */
    private static $_strings = array();

    /**
     * Initializes the language system and loads all strings (from database or cache)
     * @param   string  $lang  The name of the language pack where the strings come from
     * @access  public
     * @static
     */
    public static function init($lang) {
        self::$_lang = $lang;
        // check if a cache file exists
        $cacheFile = 'cache/lang_'.$lang.'.ini.php';
        if (core::setting('core', 'lang_cache') && file_exists($cacheFile)) {
            // load all strings from the cache file
            self::$_strings = parse_ini_file($cacheFile);
        } else {
            // load all strings from the database
            $sql = 'SELECT s.string, s.translated FROM #PREFIX#lang_strings s, #PREFIX#lang_packs p'
                 . 'WHERE p.id = s.pack AND p.code = {0}';
            $result = core::$db->query($sql, array($lang));
            foreach (core::$db->getAll($result) as $entry) {
                self::$_strings[$entry['string']] = $entry['translated'];
            }
            // write to cache if enabled
            if (core::setting('core', 'lang_cache'))
                self::writeCache();
        }
    }

    /**
     * Gets the translation of a string
     * @param   string  $string  The string to translate
     * @param   array   $vars    Variables ('%var%') to replace as array. The key is the name of the variable (without
     *                             the percent signs).
     * @return  string
     * @access  public
     * @static
     */
    public static function get($string, $vars = null) {
        if (isSet(self::$_strings[$string])) {
            $translated = self::$_strings[$string];
        } else {
            $translated = $string;
        }
        if (is_array($vars)) {
            foreach ($vars as $key => $val) {
                $translated = str_replace('%'.$key.'%', $val, $translated);
            }
        }
        return $translated;
    }

    /**
     * Writes all language strings to a cache file
     * @return  bool
     * @access  public
     * @static
     */
    public static function writeCache() {
        return utils::writeArrayToIni('cache/lang_'.self::$_lang.'.ini.php', self::$_strings);
    }

}
