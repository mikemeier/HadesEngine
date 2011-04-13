<?php
/**
 * Filter vars and check their type
 *
 * @author  Martin Lantzsch <martin@linux-doku.de>
 */
class filter {

    /**
     * Checks if a variable is a string
     * @param   mixed  $var  The variable to check
     * @return  bool
     * @access  public
     */
    public static function isString($var) {
        return is_string($var);
    }

    /**
     * Checks if a variable is a boolean value
     * @param   mixed   $var     The variable to check
     * @param   bool    $strict  If this is TRUE, FALSE is returned only for "0", "false", "off", "no", and "", and NULL
     *                             is returned for all non-boolean values
     * @return  bool
     * @access  public
     */
    public static function isBool($var, $strict = false) {
        if ($strict) {
            $optArg = array('flags' => FILTER_NULL_ON_FAILURE);
        } else {
            $optArg = array();
        }
        return filter_var($var, FITLER_VALIDATE_BOOLEAN, $optArg);
    }

    /**
     * Checks if a variable is an integer
     * @param   mixed  $var      The variable to check
     * @param   array  $options  One or more options as an array,
     *                             see {@link http://www.php.net/manual/en/filter.filters.validate.php}
     * @param   int    $flags    One or more of the FILTER_FLAG_* flags,
     *                             see {@link http://www.php.net/manual/en/filter.filters.validate.php}
     * @return  bool
     * @access  public
     */
    public static function isInt($var, $options = array(), $flags = 0) {
        $optArg = array('options' => $options, 'flags' => $flags);
        return filter_var($var, FILTER_VALIDATE_INT, $optArg);
    }

    /**
     * Checks if a variable is a float
     * @param   mixed  $var      The variable to check
     * @param   array  $options  One or more options as an array,
     *                             see {@link http://www.php.net/manual/en/filter.filters.validate.php}
     * @param   int    $flags    One or more of the FILTER_FLAG_* flags,
     *                             see {@link http://www.php.net/manual/en/filter.filters.validate.php}
     * @return  bool
     * @access  public
     */
    public static function isFloat($var, $options = array(), $flags = 0) {
        $optArg = array('options' => $options, 'flags' => $flags);
        return filter_var($var, FILTER_VALIDATE_FLOAT, $optArg);
    }

    /**
     * Checks if a variable is a string in the form of an email adress
     * @param   mixed  $var  The variable to check
     * @return  bool
     * @access  public
     */
    public static function isEmail($var) {
        return filter_var($var, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Checks if a variable is a string in the form of an URL
     * @param   mixed  $var    The variable to check
     * @param   int    $flags  One or more of the FILTER_FLAG_* flags,
     *                           see {@link http://www.php.net/manual/en/filter.filters.validate.php}
     * @return  bool
     * @access  public
     */
    public static function isURL($var, $flags = 0) {
        $optArg = array('flags' => $flags);
        return filter_var($var, FILTER_VALIDATE_URL, $optArg);
    }

    /**
     * Checks if a variable is a string in the form of an IP
     * @param   mixed  $var    The variable to check
     * @param   int    $flags  One or more of the FILTER_FLAG_* flags,
     *                           see {@link http://www.php.net/manual/en/filter.filters.validate.php}
     * @return  bool
     * @access  public
     */
    public static function isIP($var, $flags = 0) {
        $optArg = array('flags' => $flags);
        return filter_var($var, FILTER_VALIDATE_IP, $optArg);
    }

    /**
     * Checks if a variable matches a regex pattern
     * @param   mixed   $var      The variable to check
     * @param   string  $pattern  Match against this pattern
     * @return  bool
     * @access  public
     */
    public static function matchesRegex($var, $pattern = '') {
        $optArg = array('options' => array('regexp' => $pattern));
        return filter_var($var, FILTER_VALIDATE_REGEXP, $optArg);
    }

    /**
     * Removes all characters (from a string) except digits, plus and minus sign
     * @param   mixed  $var  The variable to sanitize
     * @return  int
     * @access  public
     */
    public static function int($var) {
        return filter_var($var, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * Removes all characters (from a string) except digits, plus and minus sign and optionally ., eE
     * @param   mixed  $var    The variable to sanitize
     * @param   int    $flags  One or more of the FILTER_FLAG_* flags,
     *                           see {@link http://www.php.net/manual/en/filter.filters.sanitize.php}
     * @return  float
     * @access  public
     */
    public static function float($var, $flags = 0) {
        $optArg = array('flags' => $flags);
        return filter_var($var, FILTER_SANITIZE_NUMBER_FLOAT, $optArg);
    }

    /**
     * Strips tags, optionally strips or encodes special characters (in a string)
     * @param   mixed   $var    The variable to sanitize
     * @param   int     $flags  One or more of the FILTER_FLAG_* flags,
     *                            see {@link http://www.php.net/manual/en/filter.filters.sanitize.php}
     * @return  string
     * @access  public
     */
    public static function string($var, $flags = 0) {
        $optArg = array('flags' => $flags);
        return filter_var($var, FILTER_SANITIZE_STRING, $optArg);
    }

    /**
     * Remove all characters (from a string) except letters, digits and !#$%&'*+-/=?^_`{|}~@.[]
     * @param   mixed  $var  The variable to sanitize
     * @return  string
     * @access  public
     */
    public static function sanitizeEmail($var) {
        return filter_var($var, FILTER_SANITIZE_EMAIL);
    }

    /**
     * Remove all characters (from a string) except letters, digits and $-_.+!*'(),{}|\\^~[]`<>#%";/?:@&=
     * @param   mixed  $var  The variable to sanitize
     * @return  string
     * @access  public
     */
    public static function sanitizeURL($var) {
        return filter_var($var, FILTER_SANITIZE_URL);
    }

}
