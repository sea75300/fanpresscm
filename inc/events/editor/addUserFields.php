<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\editor;

/**
 * Module-Event: addUserFields
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
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 */
final class addUserFields extends \fpcm\events\abstracts\eventReturnArray {

}
