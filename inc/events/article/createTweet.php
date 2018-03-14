<?php

/**
 * Module-Event: articleCreateTweet
 * 
 * Event wird ausgeführt, wenn Artikel gespeichert wird
 * Parameter: \fpcm\model\articles\article Artikel, aus dem ein Tweet erzeugt werden soll
 * Rückgabe: \fpcm\model\articles\article Artikel, aus dem Tweet erzeugt werden soll
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\article;

/**
 * Module-Event: article/createTweet
 * 
 * Event wird ausgeführt, wenn Artikel gespeichert wird
 * Parameter: \fpcm\model\articles\article Artikel, aus dem ein Tweet erzeugt werden soll
 * Rückgabe: \fpcm\model\articles\article Artikel, aus dem Tweet erzeugt werden soll
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/model/events
 */
final class createTweet extends \fpcm\events\abstracts\event {

}
