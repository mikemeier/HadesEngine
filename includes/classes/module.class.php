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
     */
    public static $module;

    /**
     * Initializes the module system (load modules appearing to current params)
     * @return  void
     * @access  public
     */
    public static function init() {
        // get page from params
        $module = filter::string(url::param('module'));
        if (!$module)
            $module = utils::setting('core', 'frontpage');

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
     */
    public static function loadModule($module, $action = 'main') {
        // module main class
        $moduleFile = "modules/{$module}/{$module}.php";

        // load module if exists
        if (self::exists($module)) {
            require $moduleFile;
        } else {
            throw new Exception("bootstrap: Failed loading module '{$module}'");
        }

        // check if action method exists
        if(!method_exists($module, $action))
            $action = 'main';

        // before action
        call_user_func(array($module, 'before'), $action);

        // call function
        call_user_func_array(array($module, $action), url::paramsAsArray());

        // after action
        call_user_func(array($module, 'after'), $action);
    }

    /**
     * Checks if a module exists
     * @param   string  $name  The name of the module
     * @return  bool
     * @access  public
     */
    public static function exists($module) {
        // module main class
        $moduleFile = "modules/{$module}/{$module}.php";

        // check if exists
        return file_exists($moduleFile);
    }

}
