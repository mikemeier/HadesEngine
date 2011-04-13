<?php
/**
 * This class parses the URL
 *
 * @author  Martin Lantzsch <martin@linux-doku.de>
 */
class url {

    /**
     * All params extracted from the URL
     * @var     string
     * @access  private
     */
    private static $params = array();

    /**
     * Initializes the URL parser
     * @param   string  $url  The URL to parse
     * @return  void
     * @access  public
     */
    public static function init($url) {
        $parsedParams = explode('/', $url);

        // add page and action
        array_splice($parsedParams, 0, 0, 'module');
        array_splice($parsedParams, 2, 0, 'action');

        self::$params = $parsedParams;
    }

    /**
     * Gets the value of a parameter
     * @param   string  $name  The name of the parameter
     * @return  mixed
     * @access  public
     */
    public static function param($name) {
        // serach for key of the param name
        $param = array_search($name, self::$params);
        if(is_int($param)) {
            // key plus one is the value of this param
            $key = $param + 1;
            return self::$params[$key];
        } else {
            return false;
        }
    }

    /**
     * Gets all parameters as an array
     * @return  array
     * @access  public
     */
    public static function paramsAsArray() {
        // remove page and action
        $tmp = array_slice(self::$params, 4);

        // create returning array without param names
        $isParamName = true;
        $final = array();
        foreach($tmp as $name) {
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
