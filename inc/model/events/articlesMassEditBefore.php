<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: articlesMassEditBefore
     * 
     * Event wird ausgeführt, bevor Massenbearbeitung von Artikeln ausgeführt wird
     * Parameter: array Felder und Artikel-IDs
     * Rückgabe: array Felder und Artikel-IDs
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     * @since FPCM 3.6
     */
    final class articlesMassEditBefore extends \fpcm\model\abstracts\event {

        /**
         * Konstruktor
         */
        function __construct() {
            $this->returnDataType = self::FPCM_MODULE_EVENT_RETURNTYPE_ARRAY;
            parent::__construct();
        }

        /**
         * wird ausgeführt, bevor Massenbearbeitung von Artikeln ausgeführt wird
         * @param array $data
         * @return array
         */
        public function run($data = null) {
            
            $result = parent::run($data);
            if (!count($result) || !isset($result['fields']) || !isset($result['articleIds'])) {
                return $data;
            }

            return $result;

        }
    }