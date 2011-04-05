<?php
/**
 * Filter vars and check their type
 * 
 * @author  Martin Lantzsch <martin@linux-doku.de>
 */

class filter {

    /**
     * check if var is bool
     * @param   mixed   $var
     * @return  bool
     */
    public static function isBool($var) {
        return filter_var($var, FITLER_VALIDATE_BOOLEAN);
    }

    /**
     * check if var is int
     * @param   mixed   $var
     * @return  bool
     */
    public static function isInt($var) {
        return filter_var($var, FILTER_VALIDATE_INT);
    }

    /**
     * check if var is float
     * @param   mixed   $var
     * @return  bool
     */
    public static function isFloat($var) {
        return filter_var($var, FILTER_VALIDATE_FLOAT);
    }

    /**
     * check if var is ip
     * @param   mixed   $var
     * @return  bool
     */
    public static function isIp($var) {
        return filter_var($var, FILTER_VALIDATE_IP);
    }

    /**
     * check if var is an url
     * @param   mixed   $var
     * @return  bool
     */
    public static function isUrl($var) {
        return filter_var($var, FILTER_VALIDATE_URL);
    }

    /**
     * check if var is a email adress
     * @param   mixed   $var
     * @return  bool
     */
    public static function isEmail($var) {
        return filter_var($var, FILTER_VALIDATE_EMAIL);
    }

    /**
     * check if var is a regex
     * @param   mixed   $var
     * @return  bool
     */
    public static function isRegexp($var) {
        return filter_var($var, FILTER_VALIDATE_REGEXP);
    }


    /**
     * Make a string and filter all "bad" things
     * @param   mixed   $var
     * @return  string
     */
    public static function string($var) {
        return filter_var($var, FILTER_SANITIZE_STRING);
    }

    /**
     * Make stripped and filter all "bad" things
     * @param   mixed   $var
     * @return  stripped
     */
    public static function stripped($var) {
        return filter_var($var, FILTER_SANITIZE_STRIPPED);
    }


    /**
     * Make a email and filter all "bad" things
     * @param   mixed   $var
     * @return  email
     */
    public static function email($var) {
        return filter_var($var, FILTER_SANITIZE_EMAIL);
    }

    /**
     * Make an url and filter all "bad" things
     * @param   mixed   $var
     * @return  url
     */
    public static function url($var) {
        return filter_var($var, FILTER_SANITIZE_URL);
    }

    /**
     * Make an int and filter all "bad" things
     * @param   mixed   $var
     * @return  int
     */
    public static function int($var) {
        return filter_var($var, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * Make a float and filter all "bad" things
     * @param   mixed   $var
     * @return  float
     */
    public static function float($var) {
        return filter_var($var, FILTER_SANITIZE_NUMBER_FLOAT);

    }

}