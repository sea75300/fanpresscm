<?php
    /**
     * Module-Event: editorAddUserFields
     * 
     * Event wird ausgeführt, wenn im Artikel-Editor die Link-Liste für den "Link einfügen"-Dialog geladen wird
     * Parameter: Array mit bereits vorhandenen benutzerdefinierten Feldern in der Form Beschreibung als key => Feldparamater als value
     * Rückgabe: Array mit vorhandenen benutzerdefinierten Feldern in der Form Beschreibung als key => Feldparamater als value
     * 
     * * Feld-Typen:
     * * textarea => <textarea>
     * * select => <select>
     * * checkbox => <input type="checkbox">
     * * radio => <input type="radio">
     * * textinput/unbekannt => <input type="text">
     * 
     * * Aufbau für alle Feld-Typen:
     * * * 'type' => Feld-Type
     * * * 'name' => Feld-Name
     * * * 'value' => Feld-Value
     * * * 'class' => CSS-Klasse für Feld
     * * * 'readonly' => readonly-Eigenschaft
     * 
     * * zusätzlich für Typ "textinput"
     * * * 'lenght' => maximale Textlänge
     * 
     * * zusätzlich für Typ "select"
     * * * 'options' => Optionen für Select-Box
     * * * 'firstempty' => erstes Element automatisch "Bitte wählen"
     * * * 'firstenabled' => erstes Element automatisch leer
     * 
     * * zusätzlich für Typ "checkbox" und "radio"
     * * * 'description' => Bschreibung von Checkbox/ Radiobutton
     * * * 'id' => Element-ID
     * * * 'selected' => Vorauswahl ja/nein
     * 
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: editorAddUserFields
     * 
     * Event wird ausgeführt, wenn im Artikel-Editor die Link-Liste für den "Link einfügen"-Dialog geladen wird
     * Parameter: Array mit bereits vorhandenen benutzerdefinierten Feldern in der Form Beschreibung als key => Feldparamater als value
     * Rückgabe: Array mit vorhandenen benutzerdefinierten Feldern in der Form Beschreibung als key => Feldparamater als value
     * 
     * * Feld-Typen:
     * * textarea => <textarea>
     * * select => <select>
     * * checkbox => <input type="checkbox">
     * * radio => <input type="radio">
     * * textinput/unbekannt => <input type="text">
     * 
     * * Aufbau für alle Feld-Typen:
     * * * 'type' => Feld-Type
     * * * 'name' => Feld-Name
     * * * 'value' => Feld-Value
     * * * 'class' => CSS-Klasse für Feld
     * * * 'readonly' => readonly-Eigenschaft
     * 
     * * zusätzlich für Typ "textinput"
     * * * 'lenght' => maximale Textlänge
     * 
     * * zusätzlich für Typ "select"
     * * * 'options' => Optionen für Select-Box
     * * * 'firstempty' => erstes Element automatisch "Bitte wählen"
     * * * 'firstenabled' => erstes Element automatisch leer
     * 
     * * zusätzlich für Typ "checkbox" und "radio"
     * * * 'description' => Bschreibung von Checkbox/ Radiobutton
     * * * 'id' => Element-ID
     * * * 'selected' => Vorauswahl ja/nein
     * 
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     */
    final class editorAddUserFields extends \fpcm\model\abstracts\event {

        /**
         * wird ausgeführt, wenn im Artikel-Editor die Link-Liste für den "Link einfügen"-Dialog geladen wird
         * @param void $data
         * @return array
         */
        public function run($data = null) {
            
            $eventClasses = $this->getEventClasses();
            
            if (!count($eventClasses)) return [];

            $mdata = array(
                'dummy_field' => array('name' => 'dummy', 'value' => 'dummy', 'class' => 'dummy', 'readonly' => false)
            );
            foreach ($eventClasses as $eventClass) {
                
                $classkey = $this->getModuleKeyByEvent($eventClass);                
                $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'editorAddUserFields');
                
                /**
                 * @var \fpcm\model\abstracts\event
                 */
                $module = new $eventClass();

                if (!$this->is_a($module)) continue;
                
                $mdata = $module->run($mdata);
            }
            
            array_shift($mdata);

            $mdata = array_map(array($this, 'checkFields'), $mdata);
            
            return $mdata;
            
        }
        
        /**
         * Prüft userfelder, Default-Typ ist Text
         * @param array $field
         * @return array
         */
        private function checkFields($field) {
            if (!isset($field['type'])) {
                $field['type']   = 'textinput';
                $field['lenght'] = '64';
            }
            
            return $field;
        }
    }