<?php
class url {
    private static $params = array();

    public static function init($url) {
        $tmp = explode('/', $url);
        // add page and action
        array_splice($tmp, 0, 0, 'module');
        array_splice($tmp, 2, 0, 'action');
        self::$params = $tmp;
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

    public static function paramsAsArray() {
        $tmp = self::$params;
        // remove page and action
        $delete = array(0, 1, 2, 3);
        foreach($delete as $num) {
            unset($tmp[$num]);
        }
        // return everything else
        $isParamName = true;
        $final = array();
        foreach($tmp as $name) {
            // write only param values
            if(!$isParamName) {
                $final[] = $name;
                // set var to skip the next item
                $isParamName = true;
            } else {
                $isParamName = false;
            }
        }
        return $final;
    }
}