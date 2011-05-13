<?php
/**
 * Class for generating HTML tables
 * 
 * @author  Christian Neff <christian.neff@gmail.com>
 */
class htmltable {

    /**
     * Shows a checkbox in each row to enable selecting specific records
     * @var     bool
     * @access  public
     */
    public $checkList = false;

    /**
     * A list of actions that can be executed for each record. Each item of the array contains another array
     *   with these options:
     *   - link     Link to the action. The wildcard character '*' is replaced by the row's main key value.
     *   - caption  The caption of the action
     *   - icon     Use an icon instead of a text link. Give the URL of the icon image.
     * @var     array
     * @access  public
     */
    public $actions = false;

    /**
     * Determines the name of the main key (key that contains ID) used for the actions and checkboxes
     * @var     string
     * @access  public
     */
    public $mainKey = 'id';
    
    /**
     * A list of all columns
     * @var     array
     * @access  private
     */
    private $_columns = array();
    
    /**
     * The stack of rows
     * @var     array
     * @access  private
     */
    private $_rows = array();
    
    /**
     * The class constructor
     * @param   array   $columns  An associated or indexed array listing all table columns. Each array item holds
     *                              an array containing options like heading, data type etc. The key naming of
     *                              each item must match with the key naming of the corresponding item in the
     *                              data array.
     *                              Possible options are:
     *                              - heading     The heading of the column
     *                              - datatype    The column's data type. Possible values are 'text' (default),
     *                                              'number', 'date', 'currency' or 'image'.
     *                              - style       The style attribute of each cell corresponding to this column
     *                              - width       The width of the column
     *                              - format      The format used to display the value. This option is only availabe
     *                                              if 'number', 'date' or 'currency' is selected as datatype.
     *                              - imageSrc    If 'image' is selected as data type, this option defines the
     *                                              image's 'src' attribute. The wildcard character '*' is replaced
     *                                              by the row's main key value.
     *                              - imageAlt    If image is selected as data type, this option defines the image's
     *                                              alt attribute.
     *                              - imageAttrs  If image is selected as data type, this option defines additional
     *                                              attributes for the image tag.
     *                              - sortable    Enables sorting for this column
     * @return  void
     * @access  public
     */
    public function __construct(array $columns) {
        if (empty($columns)) {
            trigger_error('htmltable: Invalid column list for table', E_USER_ERROR);
        } else {
            $this->_columns = $columns;
        }
    }
    
	/**
	 * Builds the table HTML code from all registered rows
     * @param   bool    $output  Output the generated HTML? Defaults to FALSE.
	 * @return  void
	 * @access  public
	 */
	public function build($output = false) {
	    $tpl = new tpl('table', 'core');
        $tpl->assign('columns', $this->_columns);
        $tpl->assign('rows', $this->_rows);
        $tpl->assign('checkList', $this->checkList);
        $tpl->assign('actions', $this->actions);
        $tpl->assign('mainKey', $this->mainKey);
        if (!$output) {
            return $tpl->parse();
        } else {
            echo $tpl->parse();
        }
	}
	
    /**
     * Adds a single row to the data stack
     * @param   array   $data  The input array
     * @return  void
     * @access  public
     */
    public function addDataRow(array $data) {
        $this->_rows[] = $row;
    }
    
    /**
     * Adds a set of multiple rows to the data stack
     * @param   array   $data  The input array
     * @return  void
     * @access  public
     */
    public function addDataSet(array $data) {
        foreach ($data as $row) {
            $this->_rows[] = $row;
        }
    }
    
}
