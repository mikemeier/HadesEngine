<?php
/**
 * Template parser class
 *
 * @author  Martin Lantzsch <martin@linux-doku.de>
 */
class tpl {

    /**
     * The name of the currently loaded template
     * @var     string
     * @access  private
     */
    private $tplName;

    /**
     * The name of the currently active module
     * @var     string
     * @access  private
     */
    private $moduleName;

    /**
     * All assigned template variables
     * @var     array
     * @access  private
     */
    private $vars = array();

    /**
     * The title of the page
     * @var     string
     * @access  private
     */
    private static $pageTitle;

    /**
     * All JavaScripts to include
     * @var     array
     * @access  private
     */
    private static $js = array();

    /**
     * All CSS files to include
     * @var     array
     * @access  private
     */
    private static $css = array();

    /**
     * Generates a new template object
     * @param   string   $tplName     The name of the template to load (without '.tpl.php')
     * @param   mixed    $moduleName  Load from this module
     * @return  void
     * @access  public
     */
    public function __construct($tplName, $moduleName = false) {
        $this->tplName = $tplName;
        if(!$moduleName)
            $this->moduleName = core::current('module');
        else
            $this->moduleName = $moduleName;
    }

    /**
     * Sets (a) template variable(s)
     * @param   mixed  $name   The name of the variable. If an array is given, each of its items will be set as variable
     *                           where the key is the name.
     * @param   mixed  $value  The value of the variable. If you use an array in $name, this parameter can be omitted.
     * @return  void
     * @access  public
     */
    public function set($name, $value=false) {
        // check if array
        if(!is_array($name) && $value) {
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
     * Parses the template and returns it
     * @return  string
     * @access  public
     */
    public function parse() {
        // start output
        ob_start();
        // go through all vars and define them as real ones
        foreach($this->vars as $tplVarName => $tplVarValue) {
            $$tplVarName = $tplVarValue;
        }
        // load the template file
        include 'modules/'.$this->moduleName.'/tpl/'.core::current('theme').'/'.$this->tplName.'.tpl.php';
        // return it
        return ob_get_clean();
    }

    /**
     * Sets or gets the page title
     * @param   string|bool  $title  The title
     * @return  string
     * @access  public
     */
    public static function title($title = false) {
        if($title) {
            return self::$pageTitle = $title.self::$pageTitle;
        } else {
            return self::$pageTitle;
        }
    }

    /**
     * Prints the header of the selected theme
     * @return  void
     * @access  public
     */
    public static function header() {
        // include header file
        include 'modules/core/tpl/'.core::current('theme').'/header.tpl.php';
    }

    /**
     * Prints the footer of the selected theme
     * @return  void
     * @access  public
     */
    public static function footer() {
        include 'modules/core/tpl/'.core::current('theme').'/footer.tpl.php';
    }

    /**
     * Adds a JavaScript file to the list
     * @param   string  $module  Load from this module...
     * @param   string  $name    ... this file (without '.js')
     * @param   bool    $once    Determines if the element should only be added once. Defaults to TRUE.
     * @return  bool
     */
    public static function addJS($module, $name, $once = true) {
        if ($once && in_array($name, self::$js)) {
            return false;
        }
        self::$js[] = array($module, $name);
        return true;
    }

    /**
     * Prints out the list of JavaScript files
     * @return  void
     * @access  public
     */
    public static function printJS() {
        foreach(self::$js as $entry) {
            echo '<script type="text/javascript" src="/modules/'.$entry[0].'/js/'.$entry[1].'.js"></script>'."\n";
        }
    }

    /**
     * Adds a CSS file to the list
     * @param   string  $module  Load from this module...
     * @param   string  $name    ... this file (without '.css')
     * @param   string  $media   Only for this media types. Defaults to 'all'.
     * @param   bool    $once    Determines if the element should only be added once. Defaults to TRUE.
     * @return  bool
     */
    public static function addCSS($module, $name, $media = 'all', $once = true) {
        if ($once && in_array($name, self::$css)) {
            return false;
        }
        self::$css[] = array($module, $name, $media);
        return true;
    }

    /**
     * Prints out the list of CSS files
     * @return  void
     * @access  public
     */
    public static function printCSS() {
        foreach(self::$js as $entry) {
            echo '<link rel="stylesheet" type="text/css" media="'.$entry[2].'" src="/modules/'.$entry[0].'/css/'.$entry[1].'.css" />'."\n";
        }
    }

}
