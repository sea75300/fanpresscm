<?php
    /**
     * FanPress CM spam captcha model
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\abstracts;

    /**
     * Spam Captcha base model
     * 
     * @package fpcm\model\abstracts
     * @abstract
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */ 
    abstract class spamCaptcha extends staticModel {
        
        /**
         * Captcha-Antwort prüfen
         * @return boolean, true wenn Antwort richtig
         */        
        abstract public function checkAnswer();
        
        /**
         * Weitere Prüfungen durchführen nach Antwort auf Spam-Frage
         * @return boolean
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
