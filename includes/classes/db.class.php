<?php
class db {
    public static $queryCount = 0;


    public static function connect($host, $user, $password, $database, $port=false) {
        // if port is geiven modify hostname
        if(is_int($port)) {
            $host = $host.':'.$port;
        }
        // connect to server
        $connection = mysql_connect($host, $user, $password) or die(mysql_error());
        // select database
        mysql_select_db($database, $connection) or die(mysql_error());
    }

    public static function query($sql) {
        $return = mysql_query($sql) or die(mysql_error().' <br><b>Query:</b> '.$sql);
        return $return;
        self::$queryCount++;
    }

    public static function fetch_array($query) {
        $return = mysql_fetch_array($query) or die(mysql_error());
        return $return;
    }

    public static function num_rows($query) {
        $return = mysql_num_rows($query) or die(mysql_error());
        return $return;
    }

    public static function id() {
        return mysql_insert_id();
    }
}