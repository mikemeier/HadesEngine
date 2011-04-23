<?php
/**
 * This class allows you to execute operations in a MySQL database.
 *
 * @author Christian Neff <christian.neff@gmail.com>
 */
class database {

    /**
     * The database server.
     * @var     string
     * @access  private
     */
    private $_host;

    /**
     * The username for authentificating at the database server.
     * @var     string
     * @access  private
     */
    private $_user;

    /**
     * The username for authentificating at the database server.
     * @var     string
     * @access  private
     */
    private $_password;

    /**
     * The database name.
     * @var     string
     * @access  private
     */
    private $_name;

    /**
     * The ID of the current connection.
     * @var     int
     * @access  private
     */
    private $_linkID = 0;

    /**
     * The number of already executed queries.
     * @var     int
     * @access  private
     */
    private $_queryCount = 0;

    /**
     * The cunstructur of the Database class.
     * @param   string  $host   The database server
     * @param   string  $user   The username for authentificating at the database server
     * @param   string  $pas    The username for authentificating at the database server
     * @param   string  $name   The database name
     * @return  void
     * @access  public
     */
    public function __construct($host = 'localhost', $user, $pass = '', $name) {
        $this->_host = $host;
        $this->_user = $user;
        $this->_password = $pass;
        $this->_name = $name;
        $this->connect();
    }

    /**
     * Connects to the database server and selects the required database using the given configuration. Returns
     *   a MySQL link identifier on success.
     * @return  resrc
     * @access  public
     */
    public function connect() {
        $this->_linkID = mysql_connect($this->_host, $this->_user, $this->_password);
        if (!$this->_linkID) {
            trigger_error('Failed connecting to the database', E_USER_ERROR);
        }
        $selecting_base = mysql_select_db($this->_name, $this->_linkID);
        if (!$selecting_base) {
            trigger_error('Failed selecting the database', E_USER_ERROR);
        }
        return $this->_linkID;
    }

    /**
     * Disconnects from the database server. Returns TRUE on success or FALSE on failure.
     * @return  bool
     * @access  public
     */
    public function disconnect() {
        $closed = mysql_close($this->_linkID);
        if ($closed) {
            unset($this->_linkID);
            return true;
        }
        return false;
    }

    /**
     * Sends a (optionally prepared) SQL query. For statements returning a resultset the function returns a resource on
     *   success or FALSE on failure. For other types of SQL statements it returns TRUE on success or FALSE on failure.
     * @param   string  $query  The SQL query to execute
     * @param   array   $vars   An array of values replacing the variables. Only neccessary if you're using variables.
     * @return  resrc
     * @access  public
     */
    public function query($query, $vars = null) {
        $query = $this->_prepareQuery($query, $vars);
        $result = mysql_query($query, $this->_linkID);
        $this->_queryCount++;
        if (!$result) {
            trigger_error('Database query failed. Database server logs: ' . $this->getError(), E_USER_ERROR);
        }
        return $result;
    }

    /**
     * Sends a (optionally prepared) SQL query without fetching and buffering the result rows. For statements returning
     *   a resultset the function returns a resource on success or FALSE on failure. For other types of SQL statements
     *   it returns TRUE on success or FALSE on failure.
     * @param   string  $query  The SQL query to execute
     * @param   array   $vars   An array of values replacing the variables. Only neccessary if you're using variables.
     * @return  resrc
     * @access  public
     */
    public function unbufferedQuery($query, $vars = NULL) {
        $query = $this->_prepareQuery($query, $vars);
        $result = mysql_unbuffered_query($query, $this->_linkID);
        if (!$result) {
            trigger_error('Database query failed. Database server logs: ' . $this->getError(), E_USER_ERROR);
        }
        return $result;
    }

    /**
     * Parses and executes a SQL dump file.
     * @param   string  $file  The path to the dump file
     * @param   array   $vars  An array of values replacing the variables. Only neccessary if you're using variables.
     * @return  bool
     * @access  public
     */
    public function execDump($file, $vars = null) {
        $dumpContent = file_get_contents($file);
        $queries = preg_split('/;\s*$/', $dumpContent);
        foreach($queries as $query) {
            $this->query($query, $vars);
        }
    }

    /**
     * Gets a result row as an enumerated array. Returns a numerical array that corresponds to the fetched row and moves
     *   the internal data pointer ahead.
     * @param   resrc    $result    The result resource that is being evaluated
     * @return  array
     */
    public function fetchRow($result) {
        return mysql_fetch_row($result);
    }

    /**
     * Fetches a result row as an associative array, a numeric array, or both. Returns an array of strings that
     *   corresponds to the fetched row, or FALSE if there are no more rows.
     * @param   resrc    $result    The result resource that is being evaluated
     * @return array
     * @access public
     */
    public function fetchArray($result) {
        return mysql_fetch_array($result);
    }

    /**
     * Fetches a result set as an associative array. Returns an associative array of strings that corresponds to the
     *   fetched row, or FALSE if there are no more rows.
     * @param   resrc   $result     The result resource that is being evaluated
     * @return  array
     * @access  public
     */
    public function fetchAssoc($result) {
        return mysql_fetch_assoc($result);
    }

    /**
     * Fetches the complete result set as a two-dimensional array. Returns an array as described above or FALSE on failure
     *   or if there are no rows.
     * @param   resrc   $result     The result resource that is being evaluated
     * @return  array
     * @access  public
     */
    public function fetchAll($result) {
        while ($row = mysql_fetch_assoc($result)) {
            $return[] = $row;
        }
        return $return;
    }

    /**
     * Returns the number of rows in a result set on success or FALSE on failure.
     * @param   resrc   $result     The result resource that is being evaluated
     * @return  int
     * @access  public
     */
    public function numRows($result) {
        return mysql_num_rows($result);
    }

    /**
     * Returns the number of fields in a result set on success or FALSE on failure.
     * @param   resrc   $result     The result resource that is being evaluated
     * @return  int
     * @access  public
     */
    public function numFields($result) {
        return mysql_num_fields($result);
    }

    /**
     * Returns the number of affected rows on success, and -1 if the last query failed.
     * @return  int
     * @access  public
     */
    public function affectedRows() {
        return mysql_affected_rows($this->_linkID);
    }

    /**
     * Gets the ID generated for an AUTO_INCREMENT column by the last query on success, 0 if the previous query does not
     *   generate an AUTO_INCREMENT value, or FALSE if no connection was established.
     * @return  int
     * @access  public
     */
    public function insertID() {
        return mysql_insert_id($this->_linkID);
    }

    /**
     * Escapes special characters in a string for use in a SQL statement.
     * @param   string  $string     The string that is to be escaped
     * @return  string
     * @access  public
     */
    public function escape($string) {
        return mysql_real_escape_string($string, $this->_linkID);
    }

    /**
     * Frees result memory. Returns TRUE on success or FALSE on failure.
     * @param   resrc   $result     The result resource that is being evaluated
     * @return  bool
     * @access  public
     */
    public function free_result($result) {
        return mysql_free_result($result);
    }

    /**
     * Returns the text of the error message from previous MySQL operation, or '' (empty string) if no error occurred.
     * @return  string
     * @access  public
     */
    public function getError() {
        return mysql_error($this->_linkID);
    }

    /**
     * Returns the number of already executed MySQL operations.
     * @return  int
     * @access  public
     */
    public function getQueryCount() {
        return $this->_queryCount;
    }

    /**
     * Prepares the SQL statement. Replaces '#PREFIX#' with the database prefix and '{key}' variables with the corresponding
     *   entries of $vars, if neccessary.
     * @param   string  $query  The SQL query to execute
     * @param   array   $vars   An array of values replacing the variables. Only neccessary if you're using variables.
     * @return  string
     * @accesss private
     */
    private function _prepareQuery($query, $vars = null) {
        $query = str_replace('#PREFIX#', 'he'.NR.'_', $query);
        if (is_array($vars)) {
            foreach ($vars as $key => $val) {
                if (is_numeric($val)) {
                    $valPrepared = $val;
                } elseif (is_bool($val)) {
                    $valPrepared = (int) $val;
                } elseif (is_string($val)) {
                    $val = str_replace('#PREFIX#', 'he'.NR.'_', $val);
                    $valPrepared = '"' . $this->escape($val) . '"';
                } elseif (is_array($val)) {
                    $val = str_replace('#PREFIX#', 'he'.NR.'_', $val);
                    $valPrepared = '"' . $this->escape(implode(',', $val)) . '"';
                } else {
                    $valPrepared = '""';
                }
                $query = str_replace('{'.$key.'}', $valPrepared, $query);
            }
        }
        return $query;
    }

}
