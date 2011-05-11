<?php
/**
 * Common functions
 * 
 * @author  Christian Neff <christian.neff@gmail.com>
 */

/**
 * Formats a number with grouped thousands
 * @param   float   $number          The number to be formatted
 * @param   int     $decimals        Sets the number of decimal points
 * @param   bool    $groupThousands  Enable grouping of thousands
 * @return  string
 */
function formatNumber($number, $decimals = 0, $groupThousands = false) {
    $locale = localeconv();
    return number_format($number, $decimals, $locale['decimal_point'], $groupThousands ? $locale['thousands_sep'] : '');
}

/**
 * Formats a number as a currency string
 * @param   float   $number  The number to be formatted
 * @param   string  $format  The money_format() format to use
 * @return  string
 */
function formatCurrency($number, $format = '%i') {
    return money_format($format, $number);
}

/**
 * Formats a time/date (UNIX timestamp, MySQL timestamp, string) according to locale settings
 * @param   mixed   $input   The time/date to be formatted
 * @param   string  $format  The strftime() format to use
 * @return  string
 */
function formatTime($input, $format = '%x') {
    if (empty($input)) {
        // empty input string, use current time
        $time = time();
    } elseif ($input instanceof DateTime) {
        $time = $input->getTimestamp();
    } elseif (preg_match('/^\d{14}$/', $input)) {
        // it is MySQL timestamp format of YYYYMMDDHHMMSS
        $time = mktime(substr($input, 8, 2),substr($input, 10, 2),substr($input, 12, 2),
                       substr($input, 4, 2),substr($input, 6, 2),substr($input, 0, 4));
    } elseif (is_numeric($input)) {
        // it is a numeric string, we handle it as timestamp
        $time = (int) $input;
    } else {
        // strtotime should handle it
        $strtotime = strtotime($input);
        if ($strtotime == -1 || $strtotime === false) {
            // strtotime() was not able to parse $input, use current time:
            $time = time();
        }
        $time = $strtotime;
    }
    return strftime($format, $time);
}

/**
 * Outputs/Returns the translation of a string
 * @param   string  $string  The string to translate
 * @param   bool    $output  Output the translation? Defaults to TRUE.
 * @return  mixed
 */
function t($string, $output = true) {
    if ($output) {
        echo lang::get($string);
    } else {
        return lang::get($string);
    }
}
