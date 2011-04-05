<?php
class url {
    private static $params = array();

    public function  __construct($url) {
        self::$params = explode('/', $url);
    }

    /**
     * Get value of a param
     * @param   string  $name
     * @return  mixed
     */
    public static function param($name) {
        // serach for key of the param name
        $tmp = array_search($name, self::$params);
        if(is_int($tmp)) {
            // key plus one is the value of this param
            $key = $tmp + 1;
            return self::$params[$key];
        } else {
            return false;
        }
    }
}