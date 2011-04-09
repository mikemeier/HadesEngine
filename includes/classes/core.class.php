<?php
/**
 * The core class provides all globally available objects.
 *
 * @author Christian Neff <christian.neff@gmail.com>
 */
class core {

    public static $db;

    /**
     * connect to database
     * @param   string   $host
     * @param   string   $user
     * @param   string   $password
     * @param   string   $database
     */
    public static function connectDB($host, $user, $password, $database) {
        if (!self::$db instanceof database)
            self::$db = new database($host, $user, $password, $database);
    }

}
