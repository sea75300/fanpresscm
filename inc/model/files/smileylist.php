<?php
    /**
     * Smiley file object
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\files;

    /**
     * Smiley list object
     * 
     * @package fpcm\model\files
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    final class smileylist extends \fpcm\model\abstracts\filelist {

        /**
         * Konstruktor
         */
        public function __construct() {
            $this->table    = \fpcm\classes\database::tableSmileys;
            $this->basepath = \fpcm\classes\baseconfig::$smileyDir;
            $this->exts     = image::$allowedExts;
            
            parent::__construct();
        }
        
        /**
         * Gibt Smiley-Liste in Datenbank zurück
         * @return array
         */
        public function getDatabaseList() {
            $smileys = $this->dbcon->fetch($this->dbcon->select($this->table), true);
            
            $res = [];
            foreach ($smileys as $smiley) {
                $smileyObj = new smiley($smiley->filename, false);
                $smileyObj->setSmileycode($smiley->smileycode);
                $smileyObj->setId($smiley->id);
                
                $res[$smiley->filename] = $smileyObj;
            }
            
            return $res;            
        }
        
        /**
         * Löscht mehrere Smileys auf einmal
         * @param array $items
         * @return bool
         */
        public function deleteSmileys(array $items) {
            
            $where = [];
            foreach ($items as $item) {
                $item = array_map('trim', array_map('strip_tags', $item));
                
                $where[] = "smileycode = '{$item[1]}' AND filename = '{$item[0]}' ";
            }
            
            $where = implode(' OR ', $where);

            return $this->dbcon->delete($this->table, $where);            
        }

    }
?>