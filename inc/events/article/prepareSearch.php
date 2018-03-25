<?php

/**
 * Module-Event: articlesPrepareSearch
 * 
 * Event wird ausgeführt, wenn Artikel-Suche durchgeführt wird
 * Parameter: array mit Liste der Suchparameter aus dem Suchformular
 * Rückgabe: array mit Liste der Suchparameter aus dem Suchformular
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.1
 */

namespace fpcm\events\article;

/**
 * Module-Event: article/prepareSearch
 * 
 * Event wird ausgeführt, wenn Artikel-Suche durchgeführt wird
 * Parameter: array mit Liste der Suchparameter aus dem Suchformular
 * Rückgabe: array mit Liste der Suchparameter aus dem Suchformular
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/model/events
 * @since FPCM 3.1
 */
final class prepareSearch extends \fpcm\events\abstracts\event {

    protected function getReturnType()
    {
        return '\fpcm\model\articles\search';
    }

}