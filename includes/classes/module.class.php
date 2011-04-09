<?php

/**
 * Handles modules
 *
 * @author  Martin Lantzsch <martin@linux-doku.de>
 */
class module {

    public static $module;


    public static function init() {
        // get page from params
        $module = filter::string(url::param('module'));
        if(!$module)
            $module = utils::setting('core', 'frontpage');

        self::$module = $module;

        // get action from params
        $action = filter::string(url::param('action'));
        if(!$action)
            $action = 'main';

        self::loadModule($module, $action);
    }

    public static function loadModule($module, $action='main') {
        // module main class
        $moduleFile = 'modules/'.$module.'/'.$module.'.php';

        // load module if exists
        if(file_exists($moduleFile))
            require_once $moduleFile;
        else
            throw new Exception('Bootstrap: Failed loading module {'.$module.'}');

        // check if action method exists
        if(!method_exists($module, $action))
            $action = 'main';

        // call function
        call_user_func_array(array($module, $action), url::paramsAsArray());
    }

}