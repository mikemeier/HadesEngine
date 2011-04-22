<?php
/**
 * Class for generating and validating HTML forms
 *
 * @author Christian Neff <christian.neff@gmail.com>
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
     * @param   string  $name  The form's name, used as id attribute. Also needed if there are multiple forms on a
     *                           single page.
     * @return  void
     * @access  public
     */
    public function __construct($name = null) {
        if (isset($name))
            $this->name = $name;

        $submitID = (isset($this->name) ? md5($this->name) : 1);
        if (($this->method == 'post' ? $_POST['submit'] : $_GET['submit']) == $submitID)
            $this->submitted = true;
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
        $formSettings['name'] = $this->name;
        $formSettings['action'] = $this->action !== null ? $this->action : utils::makeURL();
        $formSettings['method'] = $this->method;
        $formSettings['buttons'] = $this->buttons;

        $tpl = new tpl('form', 'core');
        $tpl->set('settings', $formSettings);
        $tpl->set('stack', $this->_stack);
        if (!$output) {
            return $tpl->parse();
        } else {
            echo $tpl->parse();
        }
    }

    /**
     * Adds a hidden field to the stack
     * @param   array   $params  The parameters of the input field
     * @return  bool
     * @access  public
     */
    public function addHidden(array $params) {
        if (!isset($params['name'])) {
            return false;
        }

        if ($this->submitted) {
            $var = $this->method == 'post' ? $_POST[$params['name']] : $_GET[$params['name']];
            $valid = $this->_validate($var, $params);
            if (!$valid) {
                $this->invalid[] = $params['name'];
            }
            $this->values[$params['name']] = $var;
            if (!isset($params['value'])) {
                $params['value'] = $this->values[$params['name']];
            }
        }

        return $this->_append('hidden', $params, isset($valid) ? $valid : true);
    }

    /**
     * Adds radiobuttons to the stack
     * @param   array   $params  The parameters of the input field
     * @return  bool
     * @access  public
     */
    public function addRadioButtons(array $params) {
        if (!isset($params['name'])) {
            return false;
        }

        if ($this->submitted) {
            $var = $this->method == 'post' ? $_POST[$params['name']] : $_GET[$params['name']];
            $valid = $this->_validate($var, $params);
            if (!$valid) {
                $this->invalid[] = $params['name'];
            }
            $this->values[$params['name']] = $var;
            if (!isset($params['value'])) {
                $params['selected'] = $this->values[$params['name']];
            }
        }

        if (!isset($params['options']) || !is_array($params['options'])) {
            return false;
        }

        return $this->_append('radiobuttons', $params, isset($valid) ? $valid : true);
    }

    /**
     * Adds a checkbox to the stack
     * @param   array   $params  The parameters of the input field
     * @return  bool
     * @access  public
     */
    public function addCheckBox(array $params) {
        if (!isset($params['name'])) {
            return false;
        }

        if ($this->submitted) {
            $var = $this->method == 'post' ? $_POST[$params['name']] : $_GET[$params['name']];
            $valid = $this->_validate($var, $params);
            if (!$valid) {
                $this->invalid[] = $params['name'];
            }
            $this->values[$params['name']] = $var;
            if (!isset($params['checked']) && $this->values[$params['name']] == $params['value']) {
                $params['checked'] = true;
            }
        }

        return $this->_append('checkbox', $params, isset($valid) ? $valid : true);
    }

    /**
     * Adds checkboxes to the stack
     * @param   array   $params  The parameters of the input field
     * @return  bool
     * @access  public
     */
    public function addCheckBoxes(array $params) {
        if (!isset($params['name'])) {
            return false;
        }

        if ($this->submitted) {
            $var = $this->method == 'post' ? $_POST[$params['name']] : $_GET[$params['name']];
            $valid = $this->_validate($var, $params);
            if (!$valid) {
                $this->invalid[] = $params['name'];
            }
            $this->values[$params['name']] = $var;
            if (!isset($params['value'])) {
                $params['selected'] = $this->values[$params['name']];
            }
        }

        if (!isset($params['options']) || !is_array($params['options'])) {
            return false;
        }

        return $this->_append('radiobuttons', $params, isset($valid) ? $valid : true);
    }

    /**
     * Adds a textinput to the stack
     * @param   array   $params  The parameters of the input field
     * @return  bool
     * @access  public
     */
    public function addTextInput(array $params) {
        if (!isset($params['name'])) {
            return false;
        }

        if ($this->submitted) {
            $var = $this->method == 'post' ? $_POST[$params['name']] : $_GET[$params['name']];
            $valid = $this->_validate($var, $params);
            if (!$valid) {
                $this->invalid[] = $params['name'];
            }
            $this->values[$params['name']] = $var;
            if (!isset($params['value'])) {
                $params['value'] = $this->values[$params['name']];
            }
        }

        return $this->_append('textinput', $params, isset($valid) ? $valid : true);
    }

    /**
     * Adds a field for uploading a file to the stack
     * @param   array   $params  The parameters of the input field
     * @return  bool
     * @access  public
     */
    public function addFileInput(array $params) {
        if (!isset($params['name'])) {
            return false;
        }

        if (!$this->_checkExists('MAX_FILE_SIZE')) {
            $this->addHidden(array(
                'name' => 'MAX_FILE_SIZE',
                'value' => (isset($params['max_file_size']) ? $params['max_file_size'] : 30000)
            ));
        }

        if ($this->submitted) {
            if (isset($_FILES[$params['name']])) {
                $this->values[$params['name']] =& $_FILES[$params['name']];
            } elseif ($params['not_empty'] == true) {
                $this->invalid[] = $params['name'];
                $valid = false;
                if (isset($options['on_error'])) {
                    if (is_callable($options['on_error'])) {
                        call_user_func($options['on_error']);
                    } elseif (is_string($options['on_error'])) {
                        displayMessage($options['message'], 'warning');
                    }
                }
            } else {
                $this->values[$params['name']] = false;
            }
        }

        return $this->_append('file', $params, isset($valid) ? $valid : true);
    }

    /**
     * Adds a dropdown field to the stack
     * @param   array   $params  The parameters of the input field
     * @return  bool
     * @access  public
     */
    public function addDropDown(array $params) {
        if (!isset($params['name'])) {
            return false;
        }

        if ($this->submitted) {
            $var = $this->method == 'post' ? $_POST[$params['name']] : $_GET[$params['name']];
            $valid = $this->_validate($var, $params);
            if (!$valid) {
                $this->invalid[] = $params['name'];
            }
            $this->values[$params['name']] = $var;
            if (!isset($params['value'])) {
                $params['selected'] = $this->values[$params['name']];
            }
        }

        return $this->_append('select', $params, isset($valid) ? $valid : true);
    }

    /**
     * Adds a date select field to the stack
     * @param   array   $params  The parameters of the input field
     * @return  bool
     * @access  public
     */
    public function addDateSelect(array $params) {
        if (!isset($params['name'])) {
            return false;
        }

        // auto add param values if none are given
        if(isset($params['day'])) {
            if(!$params['day']['min'] || $params['day']['min'] < 1) {
                $params['day']['min'] = 1;
            }
            if(!$params['day']['max'] || $params['day']['max'] > 31) {
                $params['day']['max'] = 31;
            }
        }
        if(isset($params['month'])) {
            if(!$params['month']['min'] || $params['month']['min'] < 1) {
                $params['month']['min'] = 1;
            }
            if(!$params['month']['max'] || $params['month']['max'] > 12) {
                $params['month']['max'] = 12;
            }
        }

        if ($this->submitted) {
            $var = $this->method == 'post' ? $_POST[$params['name']] : $_GET[$params['name']];
            if($this->method == 'post') {
                $var['day'] = $_POST[$params['name'].'Day'];
                $var['month'] = $_POST[$params['name'].'Month'];
                $var['year'] = $_POST[$params['name'].'Year'];
            } else {
                $var['day'] = $_GET[$params['name'].'Day'];
                $var['month'] = $_GET[$params['name'].'Month'];
                $var['year'] = $_GET[$params['name'].'Year'];
            }
            print_r($var);
            $valid = true;
            // validate year
            if($var['year'] < $params['year']['min'] || $var['year'] > $params['year']['max'] || !isset($var['year'])) {
                $valid = false;
            }
            // validate month
            if($var['month'] < $params['month']['min'] || $var['month'] > $params['month']['max'] || !isset($var['month'])) {
                $valid = false;
            }
            // validate day
            if($var['day'] < 1 || $var['day'] > date('t', mktime(0, 0, 0, $var['month'], 1, $var['year'])) || !isset($var['year'])) {
                $valid = false;
            }

            if (!$valid) {
                $this->invalid[] = $params['name'];
            }
            $this->values[$params['name'].'Day'] = $var['day'];
            $this->values[$params['name'].'Month'] = $var['month'];
            $this->values[$params['name'].'Year'] = $var['year'];
            /*$params['name']['day'] = $var['day'];
            $params['name']['month'] = $var['month'];
            $params['name']['year'] = $var['year'];*/



        }

        return $this->_append('date', $params, isset($valid) ? $valid : true);
    }

    /**
     * Adds a textarea to the stack
     * @param   array   $params  The parameters of the input field
     * @return  bool
     * @access  public
     */
    public function addTextArea(array $params) {
        if (!isset($params['name'])) {
            return false;
        }

        if ($this->submitted) {
            $var = $this->method == 'post' ? $_POST[$params['name']] : $_GET[$params['name']];
            $valid = $this->_validate($var, $params);
            if (!$valid) {
                $this->invalid[] = $params['name'];
            }
            $this->values[$params['name']] = $var;
            if (!isset($params['value'])) {
                $params['value'] = $this->values[$params['name']];
            }
        }

        return $this->_append('textarea', $params, isset($valid) ? $valid : true);
    }

    /**
     * Adds a captcha to the stack
     * @param   array   $params  The parameters of the input field
     * @return  bool
     * @access  public
     */
    public function addCaptcha(array $params) {
        if (!isset($params['name'])) {
            return false;
        }

        $captchaLength = isset($params['captcha_length']) ? $params['captcha_length'] : 5;
        $captchaFontsize = isset($params['captcha_fontsize']) ? $params['captcha_fontsize'] : 18;
        $captchaCharacters = isset($params['captcha_characters']) ? $params['captcha_characters'] : 'abcdefghijlmnpqrstuvwyzABCDEFGHIJLMNPQRSTUVWYZ123456789';
        $imageWidth = isset($params['image_width']) ? $params['image_width'] : 170;
        $imageHeight = isset($params['image_height']) ? $params['image_height'] : 60;
        // TODO: where do we save the files?
        $fontsDir = isset($params['fonts_dir']) ? $params['fonts_dir'] : HADES_DIR_ROOT . 'files/fonts/';
        $fontsList = is_array($params['fonts_list']) ? $params['fonts_list'] : array('xfiles.ttf', 'dinstik.ttf', 'hisverd.ttf');

        $params['maxlength'] = $captchaLength;

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

        for($i = 0; $i < $captchaLength; $i++) {
            $chr = $captchaCharacters[rand(0, strlen($captchaCharacters) - 1)];

            $captchaText .= $chr;

            $col4 = imageColorAllocate($img, mt_rand(0, 200), mt_rand(0, 200), mt_rand(0, 200));
            $font = $fontsDir . $fontsList[mt_rand(0, count($fontsList) - 1)];
            $y = 25 + mt_rand(0, 20);
            $angle = mt_rand(-30, 30);

            imageTTFText($img, $captchaFontsize, $angle, $x, $y, $col4, $font, $chr);

            $dim = imageTTFBbox($captchaFontsize, $angle, $font, $chr);
            $x += $dim[4] + abs($dim[6]) + 10;
        }

        $this->addHidden(array(
            'name' => $params['name'].'_hash',
            'value' => hash('sha256', $captchaText)
        ));

        ob_start();
        imageJpeg($img, null, 100);
        $params['captcha'] = ob_get_clean();

        imageDestroy($img);

        if ($this->submitted) {
            $valid = false;
            $var = $this->method == 'post' ? $_POST[$params['name']] : $_GET[$params['name']];
            $hash = $this->method == 'post' ? $_POST[$params['name'].'_hash'] : $_GET[$params['name'].'_hash'];
            if(hash('sha256', $var) == $hash) {
                $valid = true;
            }
            if (!$params['valid'] || !$valid) {
                $this->invalid[] = $params['name'];
            }
        }

        return $this->_append('captcha', $params, isset($valid) ? $valid : true);
    }

    /**
     * Checks if an input field with the given name already exists
     * @param   string   $name  The name to check
     * @return  bool
     * @access  private
     */
    private function _checkExists($name) {
        if (isset($this->_stack[$name])) {
            return true;
        }
        return false;
    }

    /**
     * Validates a variable by given options
     * @param   mixed    $var      The variable to validate
     * @param   array    $options  A list of one or more of the following options as an array:
     *                               equal - value must be equal to x
     *                               not_equal - value must not be equal to x
     *                               min_range - value must be greater than or equal to n
     *                               max_range - value must be lower than or equal to n
     *                               min_length - value's length must be at least n characters
     *                               max_length - value's length must not be greater than n characters
     *                               scheme - value must match this scheme, possible: 'email', 'url', 'ip', 'regex'
     *                               regex_pattern - value must match this regular expression, only if scheme = 'regex'
     * @return  boolean
     * @access  private
     */
    private function _validate(&$var, $options = array()) {
        $valGood = true;
        if (isset($options['equal']) && $var != $options['equal']) {
            $valGood = false;
        } else {
            if ($options['required'] && $var == '') {
                $valGood = false;
            }
            if (isset($options['not_equal']) && $var == $options['not_equal']) {
                $valGood = false;
            }
            if (isset($options['min_range']) && $var < $options['min_range']) {
                $valGood = false;
            }
            if (isset($options['max_range']) && $var > $options['max_range']) {
                $valGood = false;
            }
            if (isset($options['min_length']) && strlen($var) < $options['min_length']) {
                $valGood = false;
            }
            if (isset($options['max_length']) && strlen($var) > $options['max_length']) {
                $valGood = false;
            }
            if (isset($options['scheme']) && is_string($var)) {
                if ($options['scheme'] == 'email') {
                    $valid = filter::isEmail($var);
                } elseif ($options['scheme'] == 'url') {
                    $valid = filter::isURL($var);
                } elseif ($options['scheme'] == 'ip') {
                    $valid = filter::isIP($var);
                } elseif ($options['scheme'] == 'regex' && isset($options['regex_pattern'])) {
                    $valid = filter::matchesRegex($var, $options['regex_pattern']);
                }
                if ($valid === false) {
                    $valGood = false;
                }
            }
        }
        return $valGood;
    }

    /**
     * Appends a field to the stack
     * @param   string  $type    The type of input field
     * @param   array   $params  All the parameters
     * @param   bool    $valid   Mark the input as valid?
     * @return  void
     * @access  private
     */
    private function _append($type, array $params, $valid = true) {
        $name = $params['name'];
        if ($this->_checkExists($name)) {
            return false;
        }
        $this->_stack[$name] = array(
            'type' => $type,
            'name' => $name,
            'params' => $params,
            'valid' => $valid
        );
    }

}
