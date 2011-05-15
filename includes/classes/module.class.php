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
        $hookCache = new cache('hooks');
        if (core::setting('core', 'module_hook_cache') && $hookCache->exists) {
            // load hooks from cache
            self::$hooks = $hookCache->read();
        } else {
            // scan moddule dir
            $moduleDir = scandir('modules/');
            foreach ($moduleDir as $moduleName) {
                // skip dot dirs
                if ($moduleName[0] == '.') {
                    continue;
                }
                
                // include the module class
                include_once 'modules/'.$moduleName.'/'.$moduleName.'.php';
                
                // create a module object
                $moduleObject = new $moduleName;
                
                // get all the methods of the module and walk through them to collect the hooks
                $moduleMethods = get_class_methods($moduleObject);
                foreach ($moduleMethods as $methodName) {
                    if (substr($methodName, 0, 5) == 'hook_') {
                        self::$hooks[$methodName][] = $moduleName;
                    }
                }
                
                // unset module object
                unset($moduleObject);
            }
            
            // store result to cache if enabled
            if (core::setting('core', 'module_hook_cache')) {
                $hookCache->store(self::$hooks);
            }
        }

        // get page from params
        $module = filter::string(url::param('module'));
        if (!$module)
            $module = core::setting('core', 'frontpage');

        self::$module = $module;

        // get action from params
        $action = filter::string(url::param('action'));
        if (!$action)
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
        // load module if exists
        if (self::exists($module)) {
            require_once 'modules/'.$module.'/'.$module.'.php';
            $moduleClass = 'module_'.$module;
        } else {
            throw new Exception('module: Failed loading module \''.$module.'\'');
        }
        
        // check if action method exists
        if (!method_exists($moduleClass, $action))
            $action = 'main';
            
        // before action
        if (method_exists($moduleClass, 'before'))
            call_user_func(array($moduleClass, 'before'), $action);
            
        // call function
        call_user_func_array(array($moduleClass, 'action_'.$action), url::paramsAsArray());
        
        // after action
        if (method_exists($moduleClass, 'after'))
            call_user_func(array($moduleClass, 'after'), $action);
    }

    /**
     * Call a hook, if you want with params
     *
     * @param  string  $hook    name of the hook
     * @param  array   $params  params as array
     */
    public static function callHook($name, $params = false) {
        // look if hooks existing
        if (count(self::$hooks[$name]) != 0) {
            // go throug all plugins
            foreach (self::$hooks[$name] as $moduleName) {
                // include module class
                include_once 'modules/'.$moduleName.'/'.$moduleName.'.php';
                $moduleClass = 'module_'.$moduleName;
                
                // check if params are given
                if (!is_array($params)) {
                    // call plugin without params
                    return call_user_func(array($moduleClass, 'hook_'.$name));
                } else {
                    // call plugin with params
                    return call_user_func_array(array($moduleClass, 'hook_'.$name), $params);
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
        $moduleFile = 'modules/'.$module.'/'.$module.'.php';
        return file_exists($moduleFile);
    }

}
