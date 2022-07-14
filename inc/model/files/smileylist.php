<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * Smiley list object
 * 
 * @package fpcm\model\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class smileylist extends \fpcm\model\abstracts\filelist {

    /**
     * Konstruktor
     */
    public function __construct()
    {
        $this->table = \fpcm\classes\database::tableSmileys;
        $this->basepath = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_SMILEYS, '/');
        $this->exts = image::$allowedExts;

        parent::__construct();
    }
    
    /**
     * Gibt Smiley-Liste in Datenbank zurück
     * @param type $cached
     * @return array[]
     */
    public function getDatabaseList($cached = false)
    {
        if ($cached) {
            $stackName = 'smileysCached';
            $stack = \fpcm\classes\loader::stackPull($stackName);
            if (is_array($stack) && count($stack)) {
                return $stack;
            }
        }

        $smileys = $this->dbcon->selectFetch((new \fpcm\model\dbal\selectParams($this->table))->setFetchAll(true));

        $res = [];
        foreach ($smileys as $smiley) {
            $smileyObj = new smiley($smiley->filename, false);
            $smileyObj->setSmileycode($smiley->smileycode);
            $smileyObj->setId($smiley->id);
            $smileyObj->initImageSize();

            $res[] = $smileyObj;
        }
        
        if (!$cached) {
            return $res;
        }

        \fpcm\classes\loader::stackPush($stackName, $res);
        return $res;
    }

    /**
     * Löscht mehrere Smileys auf einmal
     * @param array $items
     * @return bool
     */
    public function deleteSmileys(array $items)
    {
        $where = [];
        $values = [];
        foreach ($items as $item) {

            $item = array_map('trim', array_map('strip_tags', $item));

            $where[] = "smileycode = ? AND filename = ?";
            $values[] = $item[1];
            $values[] = $item[0];
        }

        $where = implode(' OR ', $where);

        return $this->dbcon->delete($this->table, $where, $values);
    }

    /**
     * Returns front end smiley list string
     * @return string
     */
    public function getSmileysPublic()
    {
        $html = [];
        $html[] = "<ul class=\"fpcm-pub-smileys\">";
        foreach ($this->getDatabaseList(true) as $smiley) {           
            $html[] = '<li><a class="fpcm-pub-commentsmiley" data-code="' . $smiley->getSmileyCode() . '" href="#">'.$smiley->getImageTag().'</a></li>';
        }
        $html[] = '</ul>';

        return implode(PHP_EOL, $html);
    }
}

?>