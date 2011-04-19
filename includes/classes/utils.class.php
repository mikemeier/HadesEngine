<?php
/**
 * This class provides some good and useful things. ;-)
 *
 * @author  Martin Lantzsch <martin@linux-doku.de>
 */
class utils {

    /**
     * Generates a URL from given module, action and arguments
     * @param   string  $module  The module where the link goes to. If NULL the current module is used.
     * @param   string  $action  The action where the link goes to. If NULL the current action is used.
     * @param   array   $args    The arguments to use, optional.
     * @return  string
     */
    public static function makeURL($module = null, $action = null, $args = null) {
        $url = '?p=';
        if (is_string($module)) {
            $url .= $module;
        } else {
            $url .= core::current('module');
        }
        if (is_string($action)) {
            $url .= '/'.$action;
        } else {
            $url .= '/'.core::current('action');
        }
        if (is_array($args)) {
            foreach ($args as $arg) {
                $url .= '/'.$arg;
            }
        }
        return $url;
    }

    /**
     * Write given array to ini file
     * @param   string   $file
     * @return  array    $content
     */
    public static function writeArrayToIni($file, $content) {
        if(is_writeable($file) == true || !file_exists($file)) {
            $c = ";<?php die() ?>\n";
            foreach($content as $item1 => $item2) {
                if(is_array($item2)) {
                    $c = $c.'['.$item1."]\n";
                    foreach($item2 as $item3 => $item4) {
                        $c = $c.$item3.' = '.$item4."\n";
                    }
                } else {
                    $c = $c.$item1.' = '.$item2."\n";
                }
            }
            return file_put_contents($file, $c);
        }
    }

}
