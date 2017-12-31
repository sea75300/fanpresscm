<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: autocompleteGetData
     * 
     * Event wird ausgeführt, wenn autocomplete-Controller aufgerufen wird
     * Parameter: array mit Daten für Autocomplete
     * Rückgabe: array mit Daten für Autocomplete
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     * @since FPCm 3.6
     */
    final class autocompleteGetData extends \fpcm\model\abstracts\event {

        /**
         * Konstruktor
         */
        function __construct() {
            $this->returnDataType = self::FPCM_MODULE_EVENT_RETURNTYPE_ARRAY;
            parent::__construct();
        }

        /**
         * wird ausgeführt, wenn autocomplete-Controller aufgerufen wird
         * @param array $data
         * @return array
         */
        public function run($data = null) {
            
            $result = parent::run($data);
            if (!count($result) || !isset($result['returnData'])) {
                return $data;
            }

            return $result['returnData'];

        }
    }