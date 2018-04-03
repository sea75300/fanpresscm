<?php

/**
 * Template model
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\pubtemplates;

/**
 * Template Listen Objekt
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class templatelist extends \fpcm\model\abstracts\staticModel {

    /**
     * Gibt Liste mit allen Templates für die News/Artikel-Anzeige zurück
     * @return array
     */
    public function getArticleTemplates()
    {
        return $this->getList(glob(\fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_STYLES, 'articles/*.html')));
    }

    /**
     * Gibt Liste mit allen Templates für die Kommentar-Anzeige zurück
     * @return array
     */
    public function getCommentTemplates()
    {
        return $this->getList(glob(\fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_STYLES, 'comments/*.html')));
    }

    /**
     * Gibt Liste mit allen Templates für sonstige Anzeigen zurück
     * @return array
     */
    public function getCommonTemplates()
    {
        return $this->getList(glob(\fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_STYLES, 'common/*.html')));
    }

    /**
     * Erzeugt Template-Liste
     * @param array $templates
     * @return array
     */
    private function getList($templates)
    {
        $templateList = [];
        foreach ($templates as $template) {

            $basename = basename($template);
            if (preg_match('/^(_preview)([0-9]+)(\.html)$/i', $basename)) {
                continue;
            }

            $templateList[$basename] = substr($basename, 0, -5);
        }

        return $templateList;
    }

}
