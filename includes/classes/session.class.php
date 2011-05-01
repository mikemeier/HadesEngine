<?php
/**
 * Simple user session manager
 * 
 * @author Christian Neff <christian.neff@gmail.com>
 */
class session {

    /**
     * Determines if the session is started
     * @var     bool
     * @access  public
     */
    public $isStarted = false;
    
    /**
     * The session ID of the currently opened session
     * @var     string
     * @access  public
     */
    public $sessionID;
    
    /**
     * The data of the logged in user
     * @var     int
     * @access  public
     */
    public $userData;
    
    /**
     * The lifetime of a session in seconds
     * @var     int
     * @access  public
     */
    public $lifeTime = 3600; // 60 minutes
    
    /**
     * Constructor
     * @return  void
     * @access  public
     */
    public function __construct() {
        if (isset($_COOKIE['he_session'])) {
            $cookieSid = $_COOKIE['he_session'];
            $sql = 'SELECT * FROM #PREFIX#sessions WHERE id = {0} AND expire > {1} LIMIT 1';
            $result = core::$db->query($sql, array($cookieSid, date('Y-m-d H:i:s')));
            if (core::$db->numRows($result) == 1) {
                $session = core::$db->fetchRow($result);
                
                $this->isStarted = true;
                $this->sessionID = $session['id'];

                // fetch user data for further usage
                $sql = 'SELECT * FROM #PREFIX#users WHERE id = {0} LIMIT 1';
                $result = core::$db->query($sql, array($session['user']));
                if (core::$db->numRows($result) == 1) {
                    $this->userData = core::$db->fetchRow($result);
                }

                // refresh the session
                $this->refresh();
            }
        }
    }
    
    /**
     * Destructor
     * @return  void
     * @access  public
     */
    public function __destruct() {
        $this->cleanup();
    }
    
    /**
     * Starts a new session. Returns the session ID on success or FALSE on failure.
     * @param   int     $userID  The ID of the user who belongs to the session
     * @return  string
     * @access  public
     */
    public function start($userID) {
        if (!$this->isStarted) {
            $sessionID = $this->_genSessionID();

            // set the session cookie
            setcookie('he_session', $sessionID, 0);
            
            // register the session in the database
            $sql = 'INSERT INTO #PREFIX#sessions (id, expire, user) VALUES({0}, {1}, {2})';
            core::$db->query($sql, array($sessionID, date('Y-m-d H:i:s', time()+$this->lifeTime), $userID));

            // the session is now started, set session info
            $this->isStarted = true;
            $this->sessionID = $sessionID;

            // fetch user data for further usage
            $sql = 'SELECT * FROM #PREFIX#users WHERE id = {0}';
            $result = core::$db->query($sql, array($userID));
            if (core::$db->numRows($result) == 1) {
                $this->userData = core::$db->fetchRow($result);
            }

            return $sessionID;
        } else {
            // restart the session
            $this->destroy();
            return $this->start($userID);
        }
        return false;
    }
    
    /**
     * Destoroys the running (if the argument $sessionID is not set) or the given session
     * @param   string  $sessionID  The session ID to destroy, optional
     * @return  bool
     * @access  public
     */
    public function destroy($sessionID = null) {
        if (is_null($sessionID)) {
            if ($this->sessionID) {
                $sessionID = $this->sessionID;
                $this->isStarted = false;
                unset($this->sessionID);
                unset($this->userData);
            } else {
                return false;
            }
        }
        
        $sql = 'DELETE FROM #PREFIX#sessions WHERE id = {0}';
        return core::$db->query($sql, array($sessionID));
    }
    
    /**
     * Refreshes the running (if the argument $sessionID is not set) or the given session
     * @param   string  $sessionID  The session ID to refresh, optional
     * @return  bool
     * @access  public
     */
    public function refresh($sessionID = null) {
        if (is_null($sessionID)) {
            if ($this->sessionID) {
                $sessionID = $this->sessionID;
            } else {
                return false;
            }
        }
        
        $sql = 'UPDATE #PREFIX#sessions SET expire = {0} WHERE id = {1} LIMIT 1';
        return core::$db->query($sql, array(date('Y-m-d H:i:s', time()+$this->lifeTime), $sessionID));
    }
    
    /**
     * Delete expired sessions
     * @return  bool
     * @access  public
     */
    public function cleanup() {
        $sql = 'DELETE FROM #PREFIX#sessions WHERE expire <= {0}';
        return core::$db->query($sql, array(date('Y-m-d H:i:s')));
    }
    
    /**
     * Generates a unique session ID
     * @return  string
     * @access  private
     */
    private function _genSessionID() {
        return uniqID(mt_rand(10, 99), true);
    }
    
}
