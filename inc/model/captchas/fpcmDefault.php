<?php
    /**
     * FanPress CM Default Captcha Plugin Model
     * 
     * Default captcha plugin
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\captchas;

    /**
     * Default captcha
     * 
     * @package fpcm\model\captchas
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class fpcmDefault extends \fpcm\model\abstracts\spamCaptcha {

        /**
         * maximale Anzahl an Links, bevor Kommentar als Spam markiert wird
         * @var int
         */
        private $maxCommentTextLinks = 5;

        /**
         * Captcha-Antwort prüfen
         * @return boolean
         */
        public function checkAnswer() {
        
            if ($this->session->exists()) return true;
            
            if (!\fpcm\classes\http::get('commentCaptcha') || \fpcm\classes\http::get('commentCaptcha') != $this->config->comments_antispam_answer) {
                return false;
            }
            
            return true;
        }
        
        /**
         * zusätzliche Prüfungen durchführen
         * @return bool
         */
        public function checkExtras() {
            
            $cdata = \fpcm\classes\http::get('newcomment');
            if ($this->maxCommentTextLinks <= preg_match_all("#(https?)://\S+[^\s.,>)\];'\"!?]#", $cdata['text'])) {
                return true;
            }
            
            $comment     = new \fpcm\model\comments\comment();
            $commentList = new \fpcm\model\comments\commentList();
            
            $comment->setEmail($cdata['email']);
            $comment->setName($cdata['name']);
            $comment->setWebsite($cdata['website']);
            $comment->setIpaddress(\fpcm\classes\http::getIp());
            
            if ($commentList->spamExistsbyCommentData($comment)) {
                return true;
            }
            
            return false;
        }
        
        /**
         * Erzeugen eines Eingabefeldes für Captcha
         * @return string
         */
        public function createPluginInput() {
            if ($this->session->exists()) return '';
            
            return '<input type="text" class="fpcm-pub-textinput" name="commentCaptcha" value="">';
        }

        /**
         * Ausgabe des Captcha-Textes
         * @return string
         */
        public function createPluginText() {
            if ($this->session->exists()) return '';
            
            return $this->config->comments_antispam_question;
        }        

    }
