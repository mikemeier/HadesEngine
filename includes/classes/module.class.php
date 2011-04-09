<?php

/**
 * Handles modules
 *
 * @author  Martin Lantzsch <martin@linux-doku.de>
 */
class module {
    
    public static $module;

    /**
     * init module system (load modules appearing to current params)
     */
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

    /**
     * load module
     * @param   string    $module
     * @param   string    $action
     */
    public static function loadModule($module, $action='main') {
        // module main class
        $moduleFile = 'modules/'.$module.'/'.$module.'.php';
        // load module if exists
        if(self::exists($module))
            require $moduleFile;
        else
            throw new Exception('Bootstrap: Failed loading module {'.$module.'}');

        // check if action method exists
        if(!method_exists($module, $action))
            $action = 'main';

        // call function
        call_user_func_array(array($module, $action), url::paramsAsArray());
    }

    /**
     * check if the module exists
     * @param   string  $name
     * @return  bool
     */
    public static function exists($module) {
        // module main class
        $moduleFile = 'modules/'.$module.'/'.$module.'.php';

        // load module if exists
        return file_exists($moduleFile);
    }

}