<?php
    /**
     * FanPress CM filesystem operations model
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\files;

    /**
     * Filesystem operations object
     * 
     * @package fpcm\model\files
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    final class ops {

        /**
         * Kopiert Verzeichnis-Inhalt rekursiv
         * @param string $source
         * @param string $destination
         * @param array $exclude
         */
        public static function copyRecursive($source, $destination, $exclude = array()) {
            $dir = opendir($source);
            
            $destination = realpath($destination);

            if(!file_exists($destination)) {
                if (!mkdir($destination, 0777)) {
                    return false;
                }
            }
            
            while(false !== ( $file = readdir($dir)) ) {
                if (( $file != '.' ) && ( $file != '..' )) {
                    if ( is_dir($source . '/' . $file) ) {
                        $recres = self::copyRecursive($source . '/' . $file,$destination . '/' . $file);
                    } else {
                        if(!empty($destination) && !empty($file) && file_exists($destination . '/' . $file) && !is_writable($destination . '/' . $file)) {
                            chmod($destination . '/' . $file, 0777);
                        }
                        if(count($exclude) && in_array($file, $exclude)) continue;
                        $cpres = copy($source . '/' . $file,$destination . '/' . $file);
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
         * @since FPCM 3.1
         */
        public static function removeBaseDir($path, $keepFanPress = false) {
            
            $replacePath = \fpcm\classes\baseconfig::$baseDir;
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
        public static function deleteRecursive($path) {

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
         * @since FPCM 3.2.0
         */
        private static function deleteRecursiveExec($path) {

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

                $entryPath = $path.DIRECTORY_SEPARATOR.$entry;
                
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
                    $res = unlink ($entryPath);
                   
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
    }