<?php

class filter {

    public static function isBool($var) {
        return filter_var($var, FITLER_VALIDATE_BOOLEAN);
    }

    public static function isInt($var) {
        return filter_var($var, FILTER_VALIDATE_INT);
    }

    public static function isFloat($var) {
        return filter_var($var, FILTER_VALIDATE_FLOAT);
    }

    public static function isIp($var) {
        return filter_var($var, FILTER_VALIDATE_IP);
    }

    public static function isUrl($var) {
        return filter_var($var, FILTER_VALIDATE_URL);
    }

    public static function isEmail($var) {
        return filter_var($var, FILTER_VALIDATE_EMAIL);
    }

    public static function isRegexp($var) {
        return filter_var($var, FILTER_VALIDATE_REGEXP);
    }

    /**
     * Filter to a normal string
     *
     * @param  $var  string
     */
    public static function string($var) {
        return filter_var($var, FILTER_SANITIZE_STRING);
    }

    public static function stripped($var) {
        return filter_var($var, FILTER_SANITIZE_STRIPPED);
    }

    public static function email($var) {
        return filter_var($var, FILTER_SANITIZE_EMAIL);
    }

    public static function url($var) {
        return filter_var($var, FILTER_SANITIZE_URL);
    }

    public static function int($var) {
        return filter_var($var, FILTER_SANITIZE_NUMBER_INT);
    }

    public static function float($var) {
        return filter_var($var, FILTER_SANITIZE_NUMBER_FLOAT);

    }

}