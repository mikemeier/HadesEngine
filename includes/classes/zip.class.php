<?php
/**
* This class extracts or creates ZIP archives
*
* @author Christian Neff <christian.neff@gmail.com>
*/
class zip {

    /**
	 * The opened object of the ZipArchive class
	 * @var     object
	 * @access  private
	 */
    private $_zip;

    /**
	 * Opens a new archive. The archive is created if it does not exist.
	 * @return  void
	 * @access  public
	 */
    public function __construct($file) {
        $this->_zip = new ZipArchive;
        $res = $this->_zip->open($file, ZipArchive::CREATE);
        if ($res !== TRUE) {
            throw new Exception("zip: Failed opening archive '{$file}'");
        }
    }

    /**
	 * Extracts the complete archive or the given files to the specified destination. Returns TRUE on success or FALSE on
	 *   failure.
     * @param   string  $dest    Location where to extract the files
     * @param   mixed   $files   The files to extract. It accepts either a single file name or an array of names.
	 * @return  bool
	 * @access  public
	 */
    public function extract($dest, $files = null) {
        return $this->_zip->extractTo($dest, $files);
    }

    /**
	 * Creates a new file in the archive. Returns TRUE on success or FALSE on failure.
     * @param   string  $filename  The name of the file to create
     * @param   string  $contents  The contents to use to create the entry. It is used in a binary safe mode.
	 * @return  bool
	 * @access  public
	 */
    public function createFile($filename, $contents) {
        return $this->_zip->addFromString($filename, $contents);
    }

    /**
	 * Creates a new file in the archive. Returns TRUE on success or FALSE on failure.
     * @param   string  $localFile    The path to the source file
     * @param   string  $archiveFile  If supplied, this is the local name inside the ZIP archive that will override the
     *                                  filename.
	 * @return  bool
	 * @access  public
	 */
    public function addFile($localFile, $archiveFile = null) {
        if (is_file($localFile) && is_readable($localFile)) {
            return $this->_zip->addFile($localFile, $archiveFile);
        } else {
            throw new Exception("zip: The source file '{$localFile}' does not exist or is not readable");
        }
    }

    /**
	 * Deletes a file from the archive. Returns TRUE on success or FALSE on failure.
     * @param   mixed   $file  The name (string) or the index (int) of the file to delete
	 * @return  bool
	 * @access  public
	 */
    public function deleteFile($file) {
        if (is_int($file)) {
            return $this->_zip->deleteIndex($file);
        } elseif (is_string($file)) {
            return $this->_zip->deleteName($file);
        }
        return false;
    }

    /**
	 * Obtains information about a file in the archive. Returns an array containing the file details or FALSE on failure.
     * @param   mixed   $file  The name (string) or the index (int) of the file
	 * @return  mixed
	 * @access  public
	 */
    public function fileInfo($file) {
        if (is_int($file)) {
            return $this->_zip->statIndex($file);
        } elseif (is_string($file)) {
            return $this->_zip->statName($file);
        }
        return false;
    }

    /**
	 * Creates an empty directory in the archive. Returns TRUE on success or FALSE on failure.
     * @param   string  $dirname  The name of the directory to create
	 * @return  bool
	 * @access  public
	 */
    public function createDir($dirname) {
        return $this->_zip->addEmptyDir($dirname);
    }

    /**
	 * Adds the content of a directory recursively to the archive. Returns TRUE on success or FALSE on failure.
     * @param   string  $localDir    The path to the source directory
     * @param   string  $archiveDir  Adds the new directory to this directory in the archive. If this parameter is omitted,
     *                                 the new directory is added to the archive's root.
	 * @return  bool
	 * @access  public
	 */
    public function addDir($localDir, $archiveDir = '') {
        if (is_dir($localDir)) {
            if ($dh = opendir($localDir)) {
                if (!empty($archiveDir)) 
                    $this->createDir($archiveDir);
                while (($file = readdir($dh)) !== false) {
                    if (!is_file($localDir.$file)) {
                        if ($file !== '.' && $file !== '..')
                            $this->addDir($localDir.$file.'/', $archiveDir.$file.'/');
                    } else {
                        $this->addFile($localDir.$file, $archiveDir.$file);
                    }
                }
            }
        } else {
            throw new Exception("zip: The source directory '{$localDir}' does not exist");
        }
        return true;
    }

    /**
	 * Reverts changes done to one file or all files in the archive. Returns TRUE on success or FALSE on failure.
     * @param   mixed   $file  The name (string) or the index (int) of the file. Leave blank to revert all changes.
	 * @return  bool
	 * @access  public
	 */
    public function revertChanges($file = null) {
        if (is_int($file)) {
            return $this->_zip->unchangeIndex($file);
        } elseif (is_string($file)) {
            return $this->_zip->unchangeName($file);
        } else {
            return $this->_zip->unchangeAll();
        }
    }

    /**
	 * Gets (if $comment is omitted) or sets the comment of the archive
     * @param   string  $comment  The new comment of the archive
	 * @return  mixed
	 * @access  public
	 */
    public function comment($comment = null) {
        if (!is_null($comment)) {
            return $this->_zip->setArchiveComment($comment);
        } else {
            return $this->_zip->getArchiveComment(ZipArchive::FL_UNCHANGED);
        }
    }

    /**
	 * Closes the opened archive
	 * @return void
	 * @access public
	 */
    public function close() {
        $this->_zip->close();
    }

}
