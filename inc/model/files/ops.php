<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * FanPress CM filesystem operations model
 * @package fpcm\model\files
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class ops {

    /**
     * Kopiert Verzeichnis-Inhalt rekursiv
     * @param string $source
     * @param string $destination
     * @param array $exclude
     * @return bool
     */
    public static function copyRecursive($source, $destination, $exclude = [])
    {
        $dir = opendir($source);

        $destination = realpath($destination);

        if (!file_exists($destination)) {
            if (!mkdir($destination, 0777)) {
                return false;
            }
        }

        while (false !== ( $file = readdir($dir))) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if (is_dir($source . '/' . $file)) {
                    $recres = self::copyRecursive($source . '/' . $file, $destination . '/' . $file);
                } else {
                    if (!empty($destination) && !empty($file) && file_exists($destination . '/' . $file) && !is_writable($destination . '/' . $file)) {
                        chmod($destination . '/' . $file, 0777);
                    }
                    if (count($exclude) && in_array($file, $exclude))
                        continue;
                    $cpres = copy($source . '/' . $file, $destination . '/' . $file);
                }
            }
        }
        closedir($dir);

        return true;
    }

    /**
     * Entfernt FanPress CM baseDir-String aus einer Pfadangabe
     * @param string $path
     * @param bool $keepFanPress
     * @return string
     * @since 3.1
     */
    public static function removeBaseDir($path, $keepFanPress = false)
    {
        $replacePath = \fpcm\classes\dirs::getFullDirPath('');
        if ($keepFanPress) {
            $replacePath = dirname($replacePath);
        }

        return str_replace($replacePath, '', $path);
    }

    /**
     * Löscht Verzeichnis-Inhalt rekursiv
     * @param string $path
     * @return bool
     */
    public static function deleteRecursive($path)
    {
        if (!$path || !file_exists($path) || !is_dir($path)) {
            return false;
        }

        $res = self::deleteRecursiveExec($path);

        clearstatcache();

        if ($res < 0) {
            return false;
        }

        return true;
    }

    /**
     * Interne Funktion, welche Löschvorgang durchführt
     * @param string $path
     * @return int
     * @since 3.2.0
     */
    private static function deleteRecursiveExec($path)
    {
        if (!is_dir($path)) {
            return -1;
        }

        $dir = opendir($path);
        if (!$dir) {
            return -2;
        }

        while (($entry = readdir($dir)) !== false) {

            if ($entry == '.' || $entry == '..') {
                continue;
            }

            $entryPath = $path . DIRECTORY_SEPARATOR . $entry;

            if (is_dir($entryPath)) {
                $res = self::deleteRecursiveExec($entryPath);

                switch ($res) {
                    case -1 :
                    case -2 :
                        closedir($dir);
                        return -2;
                        break;
                    case -3 :
                        closedir($dir);
                        return -3;
                        break;
                }

                if ($res != 0) {
                    closedir($dir);
                    return -2;
                }
            } elseif (is_file($entryPath) || is_link($entryPath)) {
                $res = unlink($entryPath);

                if (!$res) {
                    closedir($dir);
                    return -2;
                }
            } else {
                closedir($dir);
                return -3;
            }
        }

        closedir($dir);
        $res = rmdir($path);

        if (!$res) {
            return -2;
        }

        return 0;
    }

    /**
     * Creates SHA256 file hash
     * @param string $path
     * @return string
     */
    public static function hashFile($path)
    {
        return hash_file(\fpcm\classes\security::defaultHashAlgo, $path);
    }
    
    /**
     * Creates upload filepath
     * @param string $path
     * @param bool $includeTime
     * @return string
     */
    public static function getUploadPath($path = DIRECTORY_SEPARATOR, $includeTime = true)
    {
        if (!$includeTime) {
            return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_UPLOADS, $path);
        }

        return \fpcm\classes\dirs::getDataDirPath(
            \fpcm\classes\dirs::DATA_UPLOADS,
            str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, date('Y-m').DIRECTORY_SEPARATOR. $path)
        );
    }

    /**
     * Creates SHA256 file hash
     * @param string $path
     * @return string
     */
    
    /**
     * Creates upload url
     * @param string $path
     * @param bool $includeTime
     * @return string
     */
    public static function getUploadUrl($path = '/', $includeTime = true)
    {
        if (!$includeTime) {
            return \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_UPLOADS, $path);
        }

        return \fpcm\classes\dirs::getDataUrl(
            \fpcm\classes\dirs::DATA_UPLOADS,
            str_replace('//', '/', date('Y-m').'/'.$path)
        );
    }

    /**
     * "realpath" wrapper for non-existing files
     * @param string $path
     * @return string
     * @since 4.5
     */
    public static function realpathNoExists(string $path) : string
    {
        $items = explode('/', $path);
        if (!count($items)) {
            return '';
        }
        
        $realpath = array_reduce($items, function ($carry, $item) use ($path) {
            
            if ($carry === 0) {
                $carry = DIRECTORY_SEPARATOR;
            }
            
            if($item === "" || $item === ".") {
                return $item;
            }
            
            if ($item == '..') {
                return dirname($carry);
            }

            return preg_replace("/\/+/", "/", "$carry/$item");
        });

        return $realpath;
        
        
    }

    /**
     * Check if fullpath is valid path in /data folder structure
     * @param string $path
     * @param string $type
     * @return bool
     * @since 4.5
     */
    public static function isValidDataFolder(string $path = '', string $type = '/') : bool
    {
        if (!trim($path)) {
            return false;
        }

        $dataPath = \fpcm\classes\dirs::getDataDirPath($type);
        $realpath = realpath($path);
        
        if (!trim($realpath)) {
            $realpath = self::realpathNoExists($path);
        }

        if (str_starts_with($realpath, $dataPath)) {
            return true;
        }
        
        trigger_error('Invalid data path found: '.$path);
        return false;
    }

}
