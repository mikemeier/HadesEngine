<?php
/**
 * Class for generating and validating HTML forms
 *
 * @author  Christian Neff <christian.neff@gmail.com>
 */
class htmlform {

    /**
     * The method attribute
     * @var     string
     * @access  public
     */
    public $method = 'post';

    /**
     * The action attribute
     * @var     string
     * @access  public
     */
    public $action;

    /**
     * A list of buttons to append at the end
     * @var     array
     * @access  public
     */
    public $buttons;

    /**
     * The form's name, used as id attribute. Also needed if there are multiple forms on a single page.
     * @var     string
     * @access  readonly
     */
    private $name;

    /**
     * Was the form submitted?
     * @var     bool
     * @access  readonly
     */
    private $submitted = false;

    /**
     * The values of all registered input fields after submission
     * @var     array
     * @access  readonly
     */
    private $values = array();

    /**
     * List of inputs with invalid values
     * @var     array
     * @access  readonly
     */
    private $invalid = array();

    /**
     * The stack of all registered input fields
     * @var     array
     * @access  private
     */
    private $_stack = array();

    /**
     * The class constructor
     * @param   string  $name  The form's name, used as id attribute. Also needed if there are multiple forms
     *                           on a single page.
     * @return  void
     * @access  public
     */
    public function __construct($name = null) {
        if (isset($name))
            $this->name = $name;

        $submitID = (isset($this->name) ? md5($this->name) : 1);
        if (($this->method == 'post' ? $_POST['submit'] : $_GET['submit']) == $submitID) {
            $this->submitted = true;
        }
    }

    /**
     * Getter for readonly properties
     * @return  mixed
     * @access  public
     */
    public function __get($varName) {
        if ($varName[0] != '_') {
            return $this->$varName;
        }
    }

    /**
     * Build form HTML code from all registered input fields
     * @param   bool    $output  Output the generated HTML? Defaults to FALSE.
     * @return  string
     * @access  public
     */
    public function build($output = false) {
        $tpl = new tpl('form', 'core');
        $tpl->set('name', $this->name);
        $tpl->set('action', $this->action !== null ? $this->action : utils::makeURL());
        $tpl->set('method', $this->method);
        $tpl->set('buttons', $this->buttons);
        $tpl->set('stack', $this->_stack);
        if (!$output) {
            return $tpl->parse();
        } else {
            echo $tpl->parse();
        }
    }

    /**
     * Adds a hidden field to the stack
     * @param   string  $name    The name of the input field
     * @param   string  $value   The value of the input field
     * @param   array   $params  The parameters of the input field
     * @return  bool
     * @access  public
     */
    public function addHidden($name, $value = '', $params = array()) {
        if ($this->fieldExists($name) || empty($name)) {
            return false;
        }
        
        if ($this->submitted) {
            $var = $this->method == 'post' ? $_POST[$name] : $_GET[$name];
            $valid = $this->_validate($var, $params);
            if (!$valid) {
                $this->invalid[] = $name;
            }
            $this->values[$name] = $var;
            $value = $this->values[$name];
        }

        $this->_stack[$name] = array(
            'type'   => 'hidden',
            'name'   => $name,
            'value'  => $value,
            'params' => $params,
            'valid'  => isset($valid) ? $valid : true
        );
        
        return true;
    }

    /**
     * Adds radiobuttons to the stack
     * @param   string  $name     The name of the input field
     * @param   array   $options  An array of options in the form:
     *                              {{<value>, <caption>}, ...}
     * @param   array   $params   The parameters of the input field
     * @return  bool
     * @access  public
     */
    public function addRadioButtons($name, $options, $params = array()) {
        if ($this->fieldExists($name) || empty($name)) {
            return false;
        }
        if (!is_array($options) || empty($options)) {
            return false;
        }
        
        if ($this->submitted) {
            $var = $this->method == 'post' ? $_POST[$name] : $_GET[$name];
            $valid = $this->_validate($var, $params);
            if (!$valid) {
                $this->invalid[] = $name;
            }
            $this->values[$name] = $var;
            $params['selected'] = $this->values[$name];
        }

        $this->_stack[$name] = array(
            'type'    => 'radiobuttons',
            'name'    => $name,
            'options' => $options,
            'params'  => $params,
            'valid'   => isset($valid) ? $valid : true
        );
        
        return true;
    }

    /**
     * Adds a checkbox to the stack
     * @param   string  $name    The name of the input field
     * @param   string  $value   The value of the input field
     * @param   array   $params  The parameters of the input field
     * @return  bool
     * @access  public
     */
    public function addCheckBox($name, $value = '', $params = array()) {
        if ($this->fieldExists($name) || empty($name)) {
            return false;
        }
        
        if ($this->submitted) {
            $var = $this->method == 'post' ? $_POST[$name] : $_GET[$name];
            $valid = $this->_validate($var, $params);
            if (!$valid) {
                $this->invalid[] = $name;
            }
            $this->values[$name] = $var;
            if ($this->values[$name] == $value) {
                $params['checked'] = true;
            }
        }

        $this->_stack[$name] = array(
            'type'   => 'checkbox',
            'name'   => $name,
            'value'  => $value,
            'params' => $params,
            'valid'  => isset($valid) ? $valid : true
        );
        
        return true;
    }

    /**
     * Adds checkboxes to the stack
     * @param   string  $name     The name of the input field
     * @param   array   $options  An array of options in the form:
     *                              {{<value>, <caption>}, ...}
     * @param   array   $params   The parameters of the input field
     * @return  bool
     * @access  public
     */
    public function addCheckBoxes($name, $options, $params = array()) {
        if ($this->fieldExists($name) || empty($name)) {
            return false;
        }
        if (!is_array($options) || empty($options)) {
            return false;
        }
        
        if ($this->submitted) {
            $var = $this->method == 'post' ? $_POST[$name] : $_GET[$name];
            $valid = $this->_validate($var, $params);
            if (!$valid) {
                $this->invalid[] = $name;
            }
            $this->values[$name] = $var;
            $params['checked'] = array();
            foreach ($options as $key => $option) {
                if ($this->values[$name][$key] == $option['value']) {
                    $params['checked'][] = $key;
                }
            }
        }

        $this->_stack[$name] = array(
            'type'    => 'checkboxes',
            'name'    => $name,
            'options' => $options,
            'params'  => $params,
            'valid'   => isset($valid) ? $valid : true
        );
        
        return true;
    }

    /**
     * Adds a textinput to the stack
     * @param   string  $name    The name of the input field
     * @param   string  $value   The value of the input field
     * @param   array   $params  The parameters of the input field
     * @return  bool
     * @access  public
     */
    public function addTextInput($name, $value = '', $params = array()) {
        if ($this->fieldExists($name) || empty($name)) {
            return false;
        }
        
        if ($this->submitted) {
            $var = $this->method == 'post' ? $_POST[$name] : $_GET[$name];
            $valid = $this->_validate($var, $params);
            if (!$valid) {
                $this->invalid[] = $name;
            }
            $this->values[$name] = $var;
            $value = $this->values[$name];
        }

        $this->_stack[$name] = array(
            'type'   => 'textinput',
            'name'   => $name,
            'value'  => $value,
            'params' => $params,
            'valid'  => isset($valid) ? $valid : true
        );
        
        return true;
    }

    /**
     * Adds a field for uploading a file to the stack
     * @param   string  $name    The name of the input field
     * @param   array   $params  The parameters of the input field
     * @return  bool
     * @access  public
     */
    public function addFileInput($name, $params = array()) {
        if ($this->fieldExists($name) || empty($name)) {
            return false;
        }
        
        $this->addHidden(array(
            'name' => 'MAX_FILE_SIZE',
            'value' => (isset($params['maxFileSize']) ? $params['maxFileSize'] : 30000)
        ));

        if ($this->submitted) {
            if (isset($_FILES[$name])) {
                $this->values[$name] =& $_FILES[$name];
            } elseif ($params['required'] == true) {
                $this->invalid[] = $name;
                $valid = false;
            } else {
                $this->values[$name] = false;
            }
        }

        $this->_stack[$name] = array(
            'type'   => 'file',
            'name'   => $name,
            'params' => $params,
            'valid'  => isset($valid) ? $valid : true
        );
        
        return true;
    }

    /**
     * Adds a dropdown field to the stack
     * @param   string  $name     The name of the input field
     * @param   array   $options  An array of options in the form:
     *                              {<value> => <caption>, ...}
     * @param   array   $params   The parameters of the input field
     * @return  bool
     * @access  public
     */
    public function addDropDown($name, $options, $params = array()) {
        if ($this->fieldExists($name) || empty($name)) {
            return false;
        }
        if (!is_array($options) || empty($options)) {
            return false;
        }
        
        if ($this->submitted) {
            $var = $this->method == 'post' ? $_POST[$name] : $_GET[$name];
            $valid = $this->_validate($var, $params);
            if (!$valid) {
                $this->invalid[] = $name;
            }
            $this->values[$name] = $var;
            $params['selected'] = $this->values[$name];
        }

        $this->_stack[$name] = array(
            'type'    => 'select',
            'name'    => $name,
            'options' => $options,
            'params'  => $params,
            'valid'   => isset($valid) ? $valid : true
        );
        
        return true;
    }

    /**
     * Adds a date select field to the stack
     * @param   string  $name    The name of the input fields
     * @param   int     $value   The currently selected date as a timestamp
     * @param   array   $params  The parameters of the input field. Additional parameters for this type are:
     *                             - minDay      List of days starts at day n
     *                             - maxDay      List of days ends at day n
     *                             - minMonth    List of months starts at month n
     *                             - maxMonth    List of months ends at month n
     *                             - minYear     List of years starts at year n
     *                             - maxYear     List of years ends at year n
     * @return  bool
     * @access  public
     */
    public function addDateSelect($name, $value = null, $params = array()) {
        if ($this->fieldExists($name) || empty($name)) {
            return false;
        }
        
        if (!isSet($params['minDay']) || $params['minDay'] < 1) {
            $params['minDay'] = 1;
        }
        if (!isSet($params['maxDay']) || $params['maxDay'] > 31) {
            $params['maxDay'] = 31;
        }
        if (!isSet($params['minMonth']) || $params['minMonth'] < 1) {
            $params['minMonth'] = 1;
        }
        if (!isSet($params['maxMonth']) || $params['maxMonth'] > 12) {
            $params['maxMonth'] = 12;
        }
        if (!isSet($params['minYear']) || $params['maxYear'] < $params['minYear']) {
            $params['minYear'] = date('Y')-70;
        }
        if (!isSet($params['maxYear']) || $params['maxYear'] < $params['minYear']) {
            $params['maxYear'] = date('Y');
        }

        if ($this->submitted) {
            if ($this->method == 'post') {
                $day = $_POST[$name.'Day'];
                $month = $_POST[$name.'Month'];
                $year = $_POST[$name.'Year'];
            } else {
                $day = $_GET[$name.'Day'];
                $month = $_GET[$name.'Month'];
                $year = $_GET[$name.'Year'];
            }
            
            $valid = true;
            if ($year < $params['minYear'] || $year > $params['maxYear'] || !isset($year)) {
                $valid = false;
            }
            if ($month < $params['minMonth'] || $month > $params['maxMonth'] || !isset($month)) {
                $valid = false;
            }
            if ($day < 1 || $day > date('t', mktime(0, 0, 0, $month, 1, $year)) || !isset($day)) {
                $valid = false;
            }

            if (!$valid) {
                $this->invalid[] = $name;
            }
            
            $this->values[$name]['day'] = $day;
            $this->values[$name]['month'] = $month;
            $this->values[$name]['year'] = $year;
        } else {
            if (!isSet($value)) {
                $value = time();
            }
            $day = date('d', $value);
            $month = date('m', $value);
            $year = date('Y', $value);
        }

        $this->_stack[$name] = array(
            'type'   => 'date',
            'name'   => $name,
            'day'    => $day,
            'month'  => $month,
            'year'   => $year,
            'params' => $params,
            'valid'  => isset($valid) ? $valid : true
        );
        
        return true;
    }

    /**
     * Adds a textarea to the stack
     * @param   string  $name    The name of the input field
     * @param   string  $value   The value of the input field
     * @param   array   $params  The parameters of the input field
     * @return  bool
     * @access  public
     */
    public function addTextArea($name, $value = '', $params = array()) {
        if ($this->fieldExists($name) || empty($name)) {
            return false;
        }
        
        if ($this->submitted) {
            $var = $this->method == 'post' ? $_POST[$name] : $_GET[$name];
            $valid = $this->_validate($var, $params);
            if (!$valid) {
                $this->invalid[] = $name;
            }
            $this->values[$name] = $var;
            $value = $this->values[$name];
        }

        $this->_stack[$name] = array(
            'type'   => 'textarea',
            'name'   => $name,
            'value'  => $value,
            'params' => $params,
            'valid'  => isset($valid) ? $valid : true
        );
        
        return true;
    }

    /**
     * Adds a captcha to the stack
     * @param   string  $name    The name of the input field
     * @param   array   $params  The parameters of the input field. Additional parameters for this type are:
     *                             - captchaLength    The number of characters on the captcha picture
     *                             - captchaFontSize  The font size of the characters
     *                             - captchaCharList  List of characters to use for the captcha
     *                             - imageWidth       The width of the captcha picture
     *                             - imageHeight      The height of the captcha picture
     *                             - fontsDir         The directory where the used fonts are stored
     *                             - fontsList        List of fonts to use for the captcha
     * @return  bool
     * @access  public
     */
    public function addCaptcha($name, $params = array()) {
        if ($this->fieldExists($name) || empty($name)) {
            return false;
        }
        
        $captchaLength = isset($params['captchaLength']) ? $params['captchaLength'] : 5;
        $captchaFontSize = isset($params['captchaFontSize']) ? $params['captchaFontSize'] : 18;
        $captchaCharList = isset($params['captchaCharList']) ? $params['captchaCharList'] : 'abcdefghijlmnpqrstuvwyzABCDEFGHIJLMNPQRSTUVWYZ123456789';
        $imageWidth = isset($params['imageWidth']) ? $params['imageWidth'] : 170;
        $imageHeight = isset($params['imageHeight']) ? $params['imageHeight'] : 60;
        $fontsDir = isset($params['fontsDir']) ? $params['fontsDir'] : HADES_DIR_ROOT . 'files/fonts/';
        $fontsList = is_array($params['fontsList']) ? $params['fontsList'] : array('xfiles.ttf', 'dinstik.ttf', 'hisverd.ttf');

        if ($params['maxlength'] < $captchaLength) {
            $params['maxlength'] = $captchaLength;
        }

        $img = imageCreateTrueColor($imageWidth, $imageHeight);

        $col1 = imageColorAllocate($img, mt_rand(170, 255), mt_rand(170, 255), mt_rand(170, 255));
        imageFill($img, 0, 0, $col1);

        $col2 = imageColorAllocate($img, mt_rand(170, 255), mt_rand(170, 255), mt_rand(170, 255));
        for($i = 0; $i < ($imageWidth * $imageHeight) / 150; $i++) {
            imageLine($img, mt_rand(0, $imageWidth), mt_rand(0, $imageHeight), mt_rand(0, $imageWidth), mt_rand(0, $imageHeight), $col2);
        }

        $col3 = imageColorAllocate($img, mt_rand(170, 255), mt_rand(170, 255), mt_rand(170, 255));
        for($i = 0; $i < ($imageWidth * $imageHeight) / 150; $i++) {
            imageLine($img, mt_rand(0, $imageWidth), mt_rand(0, $imageHeight), mt_rand(0, $imageWidth), mt_rand(0, $imageHeight), $col3);
        }

        $captchaText = '';
        $x = 10;

        $captchaCharListEnd = strlen($captchaCharList)-1;
        for($i = 0; $i < $captchaLength; $i++) {
            $chr = $captchaCharList[mt_rand(0, $captchaCharListEnd)];

            $captchaText .= $chr;

            $col4 = imageColorAllocate($img, mt_rand(0, 200), mt_rand(0, 200), mt_rand(0, 200));
            $font = $fontsDir . $fontsList[mt_rand(0, count($fontsList) - 1)];
            $y = 25 + mt_rand(0, 20);
            $angle = mt_rand(-30, 30);

            imageTTFText($img, $captchaFontSize, $angle, $x, $y, $col4, $font, $chr);

            $dim = imageTTFBbox($captchaFontSize, $angle, $font, $chr);
            $x += $dim[4] + abs($dim[6]) + 10;
        }

        $this->addHidden(array(
            'name' => $name.'_hash',
            'value' => hash('sha256', $captchaText)
        ));

        ob_start();
        imageJpeg($img, null, 100);
        $captcha = ob_get_clean();

        imageDestroy($img);

        if ($this->submitted) {
            $valid = false;
            $var = $this->method == 'post' ? $_POST[$name] : $_GET[$name];
            $hash = $this->method == 'post' ? $_POST[$name.'_hash'] : $_GET[$name.'_hash'];
            if (hash('sha256', $var) == $hash) {
                $valid = true;
            }
            if (!$params['valid'] || !$valid) {
                $this->invalid[] = $name;
            }
        }

        $this->_stack[$name] = array(
            'type'    => 'captcha',
            'name'    => $name,
            'captcha' => $captcha,
            'params'  => $params,
            'valid'   => isset($valid) ? $valid : true
        );
        
        return true;
    }

    /**
     * Checks if an input field with the given name already exists
     * @param   string   $name  The name to check
     * @return  bool
     * @access  private
     */
    public function fieldExists($name) {
        if (isset($this->_stack[$name])) {
            return true;
        }
        return false;
    }

    /**
     * Validates a variable by given options
     * @param   mixed    $var      The variable to validate
     * @param   array    $options  A list of one or more of the following options as an array:
     *                               - equal         The value must be equal to x
     *                               - notEqual      The value must not be equal to x
     *                               - minRange      The value must be greater than or equal to n
     *                               - maxRange      The value must be lower than or equal to n
     *                               - minLength     The value's length must be at least n characters
     *                               - maxLength     The value's length must not be greater than n characters
     *                               - scheme        The value must match this scheme. Possible values are
     *                                                 'email', 'url', 'ip' or 'regex'.
     *                               - matchPattern  The value must match this regular expression. This option
     *                                                 is only availabe if 'scheme' is set to 'regex'.
     * @return  bool
     * @access  private
     */
    private function _validate(&$var, $options) {
        $valGood = true;
        if (isset($options['equal']) && $var != $options['equal']) {
            $valGood = false;
        } else {
            if ($options['required'] && empty($var)) {
                $valGood = false;
            }
            if (isset($options['notEqual']) && $var == $options['notEqual']) {
                $valGood = false;
            }
            if (isset($options['minRange']) && $var < $options['minRange']) {
                $valGood = false;
            }
            if (isset($options['maxRange']) && $var > $options['maxRange']) {
                $valGood = false;
            }
            if (isset($options['minLength']) && strlen($var) < $options['minLength']) {
                $valGood = false;
            }
            if (isset($options['maxLength']) && strlen($var) > $options['maxLength']) {
                $valGood = false;
            }
            if (isset($options['scheme']) && is_string($var)) {
                if ($options['scheme'] == 'email') {
                    $valid = filter::isEmail($var);
                } elseif ($options['scheme'] == 'url') {
                    $valid = filter::isURL($var);
                } elseif ($options['scheme'] == 'ip') {
                    $valid = filter::isIP($var);
                } elseif ($options['scheme'] == 'regex' && isset($options['matchPattern'])) {
                    $valid = filter::matchesRegex($var, $options['matchPattern']);
                }
                if ($valid === false) {
                    $valGood = false;
                }
            }
        }
        return $valGood;
    }

}
