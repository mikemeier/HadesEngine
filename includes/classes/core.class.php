<?php
/**
 * The core class provides all globally available objects.
 *
 * @author Christian Neff <christian.neff@gmail.com>
 */
class core {

    public static $db;

    public static function connectDB($host, $user, $password, $database) {
        if (self::$db !instanceof database)
            self::$db = new database($host, $user, $password, $database);
    }

}
