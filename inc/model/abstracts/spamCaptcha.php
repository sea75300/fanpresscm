<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\abstracts;

/**
 * Spam Captcha base model
 * 
 * @package fpcm\model\abstracts
 * @abstract
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
abstract class spamCaptcha extends staticModel {

    /**
     * Captcha-Antwort prüfen, true wenn Antwort richtig
     * @return bool
     */
    abstract public function checkAnswer();

    /**
     * Weitere Prüfungen durchführen nach Antwort auf Spam-Frage
     * @return bool
     */
    abstract public function checkExtras();

    /**
     * Erzeugen eines Eingabefeldes für Captcha
     * @return string
     */
    abstract public function createPluginText();

    /**
     * Ausgabe des Captcha-Textes, Bildes, etc.
     * @return string
     */
    abstract public function createPluginInput();
}
