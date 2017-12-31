<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\abstracts;

    /**
     * Object search wrapper object
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm\model\comments
     * @since FPCM 3.5
     */ 
    abstract class searchWrapper extends staticModel {

        /**
         * Liefert Daten zurück, die über Eigenschaften erzeugt wurden
         * @return array
         */
        public function getData() {
            return $this->data;
        }

        /**
         * Funktion liefert Informationen zurpck, ob Suchparameter vorhanden
         * @return bool
         */
        public function hasParams() {
            return count($this->data) ? true : false;
        }

    }
