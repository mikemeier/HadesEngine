<?php

/**
 * This class handles all module calls
 *
 * @author  Martin Lantzsch <martin@linux-doku.de>
 */
class module {

    /**
     * The currently loaded module
     * @var     string
     * @access  public
     * @static
     */
    public static $module;

    /**
     * All availible hooks
     * @var     attay
     * @access  private
     * @static
     */
    private static $hooks = array();

    /**
     * Initializes the module system (load modules appearing to current params)
     * @return  void
     * @access  public
     * @static
     */
    public static function init() {
        // start hook system
        if(core::setting('core', 'module_hook_cache') == true) {

        } else {
            $moduleDir = scandir('modules/');
            unset($moduleDir[0]);
            unset($moduleDir[1]);
            foreach($moduleDir as $moduleName) {
                $file = 'modules/'.$moduleName.'/'.$moduleName.'.php';
                include $file;
                // make an object
                $theModule = new $moduleName;
                // get methods as hooks
                $hooks = get_class_methods($theModule);
                // go through all hooks
                foreach($hooks as $hookName) {
                    // load only hooks without underscore as first char
                    if(substr($hookName, 0, 5) == 'hook_') {
                        echo 1;
                        // put to plugins array
                        self::$hooks[$hookName][] = $moduleName;
                    }
                }
                unset($theModule);
            }
        }

        // get page from params
        $module = filter::string(url::param('module'));
        if(!$module)
            $module = core::setting('core', 'frontpage');

        self::$module = $module;

        // get action from params
        $action = filter::string(url::param('action'));
        if(!$action)
            $action = 'main';

        self::loadModule($module, $action);
    }

    /**
     * Loads a module and executes the given action
     * @param   string  $module  The name of the module
     * @param   string  $action  The name of the action
     * @return  void
     * @access  public
     * @static
     */
    public static function loadModule($module, $action = 'main') {
        // module main class
        $moduleFile = "modules/{$module}/{$module}.php";

        // load module if exists
        if(self::exists($module)) {
            require $moduleFile;
        } else {
            throw new Exception("bootstrap: Failed loading module '{$module}'");
        }

        // check if action method exists
        if(!method_exists($module, $action))
            $action = 'main';

        // before action
        if(method_exists($module, 'before'))
            call_user_func(array($module, 'before'), $action);

        // call function
        call_user_func_array(array($module, 'action_'.$action), url::paramsAsArray());

        // after action
        if(method_exists($module, 'after'))
            call_user_func(array($module, 'after'), $action);
    }

    /**
     * Call a hook, if you want with params
     *
     * @param  string  $hook    name of the hook
     * @param  array   $params  params as array
     */
    public static function callHook($name, $params=false) {
        // look if hooks existing
        if(count(self::$hooks[$name]) != 0) {
            // go throug all plugins
            foreach(self::$hooks[$name] as $moduleName) {
                // include module class if
                if(core::setting('core', 'module_hook_cache') == true) {
                    include "modules/{$moduleName}/{$moduleName}.php";
                }

                // check if params are given
                if(!is_array($params)) {
                    // call plugin without params
                    call_user_func(array($moduleName, 'hook_'.$hook));
                } else {
                    // call plugin with params
                    call_user_func_array(array($moduleName, 'hook_'.$hook), $params);
                }
            }
        }
    }

    /**
     * Checks if a module exists
     * @param   string  $name  The name of the module
     * @return  bool
     * @access  public
     * @static
     */
    public static function exists($module) {
        // module main class
        $moduleFile = "modules/{$module}/{$module}.php";

        // check if exists
        return file_exists($moduleFile);
    }

}
