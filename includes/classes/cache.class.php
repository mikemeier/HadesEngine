<?php
/**
 * Class for evaluating cache files
 *
 * @author  Christian Neff <christian.neff@gmail.com>
 */
class cache {

    /**
     * Does the cache file exist?
     * @var     bool
     * @access  readonly
     */
    private $exists = false;

    /**
     * The name of the cache file
     * @var     string
     * @access  private
     */
    private $_fileName;

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
     * The class constructor
     * @param   string  $cacheFile  The name of the cache file to load
     * @return  void
     * @access  public
     */
    public function __construct($cacheFile) {
        $this->_fileName = 'cache/'.$cacheFile.'.cache.php';
        if (file_exists($this->_fileName)) {
            $this->exists = true;
        }
    }

    /**
     * Read the cache file
     * @return  mixed
     * @access  public
     */
    public function read() {
        $serialized = substr(file_get_contents($this->_fileName), 14);
        return unserialize($serialized);
    }

    /**
     * Write data to the cache file
     * @param   mixed   $data  The data to store to the cache file
     * @return  bool
     * @access  public
     */
    public function store($data) {
        $contents = '<?php die() ?>'.serialize($data);
        return file_put_contents($this->_fileName, $contents);
    }
    
}
