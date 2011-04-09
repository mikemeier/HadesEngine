<?php
class tpl {
    private $tplName;
    private $moduleName;
    private $vars = array();
    private static $pageTitle;
    private static $js = array();
    private static $css = array();

    /**
     * Generate new template object
     * @param   string   $tplName
     * @param   mixed    $moduleName
     */
    public function  __construct($tplName, $moduleName=false) {
        $this->tplName = $tplName;
        if(!$moduleName)
            $this->moduleName = utils::current('module');
        else
            $this->moduleName = $moduleName;
    }

    /**
     * Set new template var(s)
     * (if you give an array as $name all vars of this array will be set)
     * @param   mixed  $name
     * @param   mixed  $value
     */
    public function set($name, $value=false) {
        // check if array
        if(!is_array($name) & !$value) {
            // assign var to array
            $this->vars[$name] = $value;
        } else {
            // assign all array vars
            foreach($name as $name => $value) {
                $this->vars[$name] = $value;
            }
        }
    }

    /**
     * Parse template and return it
     * @return  string
     */
    public function parse() {
        // start output
        ob_start();

        // go through all vars
        foreach($this->vars as $tplVarName => $tplVarValue) {
            $$tplVarName = $tplVarValue;
        }

        // load template
        include 'modules/'.$this->moduleName.'/tpl/'.$this->tplName.'.tpl.php';

        // give all output
        return ob_get_clean();
    }

    /**
     * Set or get the page title
     * @param   string/bool  $title
     * @return  string
     */
    public static function title($title=false) {
        if($title)
            return self::$pageTitle = $title.self::$pageTitle;
        else
            return self::$pageTitle;
    }

    /**
     * Print header of selected theme
     */
    public static function header() {
        // include header file
        include 'themes/'.utils::current('theme').'/header.tpl.php';
    }

    /**
     * Print footer of the selected theme
     */
    public static function footer() {
        include 'themes/'.utils::current('theme').'/footer.tpl.php';
    }

    public static function addJS($module, $name) {
        self::$js[$module] = $name;
    }

    public static function printJS() {
        foreach(self::$js as $module => $name) {
            echo "<script type='text/javascript' src='/modules/".$module."/".$name.".js'></script>\n";
        }
    }

    public static function addCSS($module, $name) {
        self::$css[$module] = $name;
    }

    public static function printCSS() {
        echo "<style type='text/css'>\n";
        foreach(self::$css as $module => $name) {
            echo "@import url('/modules/".$module."/".$name.".js');\n";
        }
        echo "</style>\n";
    }
}