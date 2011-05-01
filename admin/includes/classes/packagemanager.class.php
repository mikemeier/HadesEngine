<?php
/**
 * This class allows a simple management of packages
 *
 * @author Christian Neff <christian.neff@gmail.com>
 */
class packagemanager {

    /**
     * The URL of the package archive server
     * @var     string
     * @access  public
     * @static
     */
    public static $archiveServer;

    /**
     * Installs a package. Returns TRUE on success or FALSE on failure.
     * @param   string  $packName  The name of the package
     * @return  bool
     * @access  public
     * @static
     */
    public static function install($packName) {
        $packUploadDir = HADES_DIR_ROOT.'/files/temp';
        $packDestDir = HADES_DIR_ROOT.'/files/packages';
        // open the package
        $pack = new zip($packUploadDir.'/'.$packName.'.zip');
        // load package info
        $packInfo = parse_ini_string($pack->comment());
        // validate fetched release number
        if ((int) $packInfo['release'] < 1) {
            throw new Exception('The package \''.$packName.'\' does not provide a valid release number.');
        }
        // check if already installed and fetch info if so
        $packInfoLocal = self::isInstalled($packName);
        if ($packInfoLocal) {
            // compare version numbers to check if the install is neccessary
            if ($packInfoLocal['release'] >= $packInfo['release'])) {
                throw new Exception('The package \''.$packName.'\' is already installed and up to date.');
            }
        }
        // backup changed files and database
            
        // extract the package
        $pack->extract($packUploadDir);
        $packExtracted = $packUploadDir.'/'.$packName.'.pack';
        // update or fresh install?
        $setup = parse_ini_file($packExtracted.'/setup.ini', true);
        if ($packInfoLocal) {
            // update the package
            $releaseDiff = $packInfo['release']-$packInfoLocal['release'];
            for ($u = $packInfoLocal['release']+1; $u <= $packInfo['release']; $u++) {
                if (!isSet($setup["update $u"]))
                    continue;
                $instr = $setup["update $u"];
                // move all files to Hades root dir
                foreach ($instr['add'] as $file) {
                    rename($packExtracted.'/files/'.$file, HADES_DIR_ROOT.'/'.$file);
                }
                // update database with install.sql
                
                // update package registry
                
            }
        } else {
            $instr = $setup['install'];
            // move all files to Hades root dir
            foreach ($instr['add'] as $file) {
                rename($packExtracted.'/files/'.$file, HADES_DIR_ROOT.'/'.$file);
            }
            // update database with install.sql
            
            // register package in registry
            
        }
    }

    /**
     * Downloads a package from the archive server. Returns TRUE on success or FALSE on failure.
     * @param   string  $packName  The name of the package
     * @return  bool
     * @access  public
     * @static
     */
    public static function download($packName) {
        // contact server to get download link
        
        // download package file to temp
        
    }

    /**
     * Uninstalls a package. Returns TRUE on success or FALSE on failure.
     * @param   string  $packName  The name of the package
     * @return  bool
     * @access  public
     * @static
     */
    public static function uninstall($packName) {
        
    }

    /**
     * Removes a package completely. Returns TRUE on success or FALSE on failure.
     * @param   string  $packName  The name of the package
     * @return  bool
     * @access  public
     * @static
     */
    public static function remove($packName) {
        // uninstall the package
        self::uninstall($packName);
        // remove configuration
        
    }

    /**
     * Checks if a package is installed. Returns the package info if it is installed or FALSE otherwise.
     * @param   string  $packName  The name of the package
     * @return  array
     * @access  public
     * @static
     */
    public static function isInstalled($packName) {
        
    }

    /**
     * Compares two version strings in the form 'x.x.x'. Returns 1 if $localVer < $remoteVer, 2 if $localVer = $remoteVer,
     *   3 if $localVer > $remoteVer or FALSE on failure.
     * @param   string  $localVer   The first version string to compare
     * @param   string  $remoteVer  The second version string to compare
     * @return  mixed
     * @access  private
     * @static
     */
    private static function _compareVersion($localVer, $remoteVer) {
        $localVerExpNew = $localVerExp = explode(".", $localVer);
        $remoteVerExpNew = $remoteVerExp = explode(".", $remoteVer);
        $localVerGroups = count($localVerExp);
        $remoteVerGroups = count($remoteVerExp);
        $maxGroups = (int) max($localVerGroups, $remoteVerGroups);
        for ($x = $maxGroups-1; $x >= 0; $x--) {
            if (isset($localVerExp[$x])) {
                if (!is_numeric($localVerExp[$x]))
                    return false;
            } else {
                $localVerExpNew[] = 0;
            }
            if (isset($remoteVerExp[$x])) {
                if (!is_numeric($remoteVerExp[$x]))
                    return false;
            } else {
                $remoteVerExpNew[] = 0;
            }
        }
        if (implode('.', $localVerExpNew) != implode('.', $remoteVerExpNew)) {
            for ($x = 0; $x != $maxGroups; $x++) {
                if ($localVerExpNew[$x] != $remoteVerExpNew[$x]) {
                    if ($localVerExpNew[$x] > $remoteVerExpNew[$x]) {
                        return 3;
                    } else {
                        return 1;
                    }
                }
            }
        } else {
            return 2;
        }
    }

}
