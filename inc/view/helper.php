<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view;

/**
 * View Helper
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\view
 */
final class helper {

    /**
     * Objekt vom typ \fpcm\classes\language
     * @var \fpcm\classes\language
     */
    protected static $language;

    /**
     * Sprache für View-Helper initialisieren
     * @param string $langCode
     */
    public static function init($langCode)
    {
        self::$language = \fpcm\classes\loader::getObject('\fpcm\classes\language', $langCode);
    }

    /**
     * Erzeugt Speichern Button
     * @param string $name Name des Buttons
     */
    public static function saveButton($name)
    {
        new helper\saveButton($name);
    }

    /**
     * Erzeugt Speichern Button
     * @param string $name Name des Buttons
     */
    public static function deleteButton($name)
    {
        new helper\deleteButton($name);
    }

    /**
     * Erzeugt Zuürcksetzen Button
     * @param string $name Name des Buttons
     */
    public static function resetButton($name)
    {
        new helper\resetButton($name);
    }

    /**
     * 
     * Erzeugt Senden Button
     * @param string $name Name des Buttons
     * @param string $descr Beschreibung des Buttons
     * @param string $class CSS-Klasse
     */
    public static function submitButton($name, $descr, $class = '')
    {
        (new helper\submitButton($name))->setText($descr)->setClass($class);
    }

    /**
     * Erzeugt Button
     * @param string $type Button-Type
     * @param string $name Name des Buttons
     * @param string $descr Beschreibung des Buttons
     * @param string $class CSS-Klasse
     */
    public static function button($type, $name, $descr, $class = '')
    {
        (new helper\button($name))->setType($type)->setText($descr)->setClass($class);
    }

    /**
     * Erzeugt Link-basierten Button
     * @param string $href Ziel-URL
     * @param string $descr Beschreibung
     * @param string $id Button-ID
     * @param string $class CSS-Klasse
     * @param string $target Link-Target
     */
    public static function linkButton($href, $descr, $id = '', $class = '', $target = '_self')
    {
        (new helper\linkButton(uniqid('_lkbtn', $id)))
                ->setText($descr)
                ->setClass($class)
                ->setUrl($href)
                ->setTarget($target);
    }

    /**
     * Erzeugt Button-Dummy
     * @param string $descr Beschreibung
     * @param string $class CSS-Klasse
     */
    public static function dummyButton($descr, $class = '')
    {
        (new helper\button(uniqid('_dmbtn')))
                ->setText($descr)
                ->setClass($class)
                ->setReadonly(true);
    }

    /**
     * Erzeugt Link-basierte Bearbeiten-Button
     * @param string $href Ziel-URL Ziel-URL
     * @param bool $active Button ist aktiv
     * @param string $class CSS-Klasse
     */
    public static function editButton($href, $active = true, $class = '')
    {
        (new helper\editButton(uniqid('_edbtn')))
                ->setClass($class)->setReadonly($active ? false : true)
                ->setUrl($href);
    }

    /**
     * Erzeugt Input
     * @param string $name Name des Buttons
     * @param string $class CSS-Klasse
     * @param string $value Wert
     * @param bool $readonly readonly Status
     * @param int $maxlength maximale Länge für Feld-Eingabe
     * @param string $placeholder Platzhalter-Text
     * @param string $wrapper Wrapper-DIV nutzen
     */
    public static function textInput($name, $class = '', $value = '', $readonly = false, $maxlength = 255, $placeholder = false, $wrapper = true)
    {
        (new helper\textInput($name))
                ->setClass($class)
                ->setValue($value)
                ->setReadonly($readonly)
                ->setMaxlenght($maxlength)
                ->setPlaceholder($placeholder ? true : false)
                ->setText(is_string($placeholder) ? $placeholder : '')
                ->setWrapper(is_string($wrapper) || $wrapper === true ? true : false)
                ->setWrapperClass(is_string($wrapper) ? $wrapper : '');
    }

    /**
     * Erzeugt Passwort-Input
     * @param string $name Name des Buttons
     * @param string $class CSS-Klasse
     * @param string $value Wert
     * @param bool $readonly readonly Status
     * @param int $maxlength maximale Länge für Feld-Eingabe
     * @param sting $placeholder HTML5-Platzhalter-Text
     * @param string $wrapper Wrapper-DIV nutzen
     */
    public static function passwordInput($name, $class = '', $value = '', $readonly = false, $maxlength = 255, $placeholder = false, $wrapper = true)
    {
        (new helper\passwordInput($name))
                ->setClass($class)
                ->setValue($value)
                ->setReadonly($readonly)
                ->setMaxlenght($maxlength)
                ->setPlaceholder($placeholder ? true : false)
                ->setText(is_string($placeholder) ? $placeholder : '')
                ->setWrapper($wrapper ? true : false)
                ->setWrapperClass(is_string($wrapper) ? $wrapper : '');
    }

    /**
     * Erzeugt Checkbox
     * @param string $name Name des Buttons
     * @param string $class CSS-Klasse
     * @param string $value Wert
     * @param string $descr Beschreibung
     * @param string $id Button-ID
     * @param bool $selected Checkbox ist vorausgewählt
     * @param bool $readonly readonly Status
     */
    public static function checkbox($name, $class = '', $value = '', $descr = '', $id = '', $selected = true, $readonly = false)
    {
        (new helper\checkbox($name, $id))
                ->setClass($class)
                ->setValue($value)
                ->setText($descr)
                ->setReadonly($readonly)
                ->setSelected($selected);
    }

    /**
     * Erzeugt Textarea
     * @param string $name Name des Buttons
     * @param string $class CSS-Klasse
     * @param string $value Wert
     * @param bool $readonly readonly Status
     */
    public static function textArea($name, $class = '', $value = '', $readonly = false)
    {
        (new helper\textarea($name))->setClass($class)->setValue($value, ENT_QUOTES)->setReadonly($readonly);
    }

    /**
     * Erzeugt Select-Menü
     * @param string $name Name des Buttons
     * @param array $options Array mit Optionen für das Auswahlmenü
     * @param string $selected vorausgewähltes Element
     * @param bool $firstEmpty erstes Element soll leer sein
     * @param bool $firstEnabled erstes Element wird automatisch erzeugt
     * @param bool $readonly readonly Status
     * @param string $class CSS-Klasse
     */
    public static function select($name, $options, $selected = null, $firstEmpty = false, $firstEnabled = true, $readonly = false, $class = '')
    {
        $firstOption    = $firstEnabled && $firstEmpty
                        ? helper\select::FIRST_OPTION_EMPTY
                        : ($firstEnabled ? helper\select::FIRST_OPTION_PLEASESELECT : helper\select::FIRST_OPTION_DISABLED);

        (new helper\select($name))
                ->setClass($class)
                ->setSelected($selected)
                ->setOptions($options)
                ->setReadonly($readonly)
                ->setFirstOption($firstOption);
    }

    /**
     * Erzeugt gruppiertes Select-Menü
     * @param string $name Name des Buttons
     * @param array $options Array mit Optionen für das Auswahlmenü
     * @param string $selected vorausgewähltes Element
     * @param bool $readonly readonly Status
     */
    public static function selectGroup($name, $options, $selected = null, $readonly = false)
    {
        (new helper\select($name))
                ->setSelected($selected)
                ->setOptions($options)
                ->setReadonly($readonly)
                ->setOptGroup(true);
    }

    /**
     * Liefert "Keine Einträge gefunden"-Zeile zurück
     * @param array $data
     * @param int $cols
     * @return void
     */
    public static function notFoundContainer(array $data, $cols)
    {
        if (count($data)) {
            return false;
        }

        print "<tr class=\"fpcm-td-spacer\"><td colspan=\"{$cols}\"></td></tr><tr><td colspan=\"{$cols}\">" . self::$language->translate('GLOBAL_NOTFOUND2') . "</td></tr>";
    }

    /**
     * Progressbar-DIV
     * @param string $progressbarName
     * @since FPCM 3.2.0
     */
    public static function progressBar($progressbarName)
    {
        if (!$progressbarName) {
            return;
        }

        include_once \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'components/progress.php');
    }

}

?>