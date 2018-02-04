<?php

/**
 * FanPress CM 3.x
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
        (new helper\linkButton(uniqid('_lkbtn')))
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
                ->setReadonly(true)
                ->setType('button');
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
     * Erzeugt hidden Inout-Feld
     * @param string $name Name des Buttons
     * @param string $value Wert
     */
    public static function hiddenInput($name, $value = '')
    {
        (new helper\hiddenInput($name))->setValue($value);
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
        (new helper\checkbox($name))
                ->setClass($class)
                ->setValue($value)
                ->setText($descr)
                ->setReadonly($readonly)
                ->setSelected($selected);
    }

    /**
     * Erzeugt Radiobutton
     * @param string $name Name des Buttons
     * @param string $class CSS-Klasse
     * @param string $value Wert
     * @param string $descr Beschreibung
     * @param string $id Button-ID
     * @param bool $selected Checkbox ist vorausgewählt
     * @param bool $readonly readonly Status
     */
    public static function radio($name, $class = '', $value = '', $descr = '', $id = '', $selected = true, $readonly = false)
    {
        (new helper\radiobutton($name))
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
     * Setzt bool-Wert in Text ja/nein um
     * @param bool $value
     * @param string $text
     */
    public static function boolToText($value, $text = 'GLOBAL_YES')
    {
        (new helper\boolToText(uniqid('_dmbtn')))->setValue($value)->setText($text);
    }

    /**
     * Erzeugt Ja/nein Select-Menü
     * @param string $name Name des Buttons
     * @param array $selected
     * @param bool $readonly readonly Status
     * @param string $class CSS-Klasse
     * @return string
     */
    public static function boolSelect($name, $selected, $readonly = false, $class = '')
    {
        (new helper\boolSelect($name))
                ->setSelected($selected)
                ->setReadonly($readonly)
                ->setClass($class);
    }

    /**
     * Vier-Helper für einheitliche Ausgabe von Datumsangaben
     * @param int $timespan Zeitstempel
     * @param string $format Datumsformat, überschreibt "system_dtmask"
     * @param strig $return Datum-String zurückgeben und nicht in Ausgabe schreiben
     * @since FPCM 3.2.0
     */
    public static function dateText($timespan, $format = false, $return = false)
    {
        if (!$format) {
            $format = \fpcm\classes\loader::getObject('\fpcm\model\system\config')->system_dtmask;
        }

        $timespan = date($format, $timespan);

        if ($return) {
            return $timespan;
        }

        print $timespan;
    }

    /**
     * CSS-Klassen-Containter für Button-Toolbar
     * @return void
     */
    public static function buttonsContainerClass()
    {
        print 'fpcm-buttons fpcm-buttons-fixed';
    }

    /**
     * Werte in View escapen als Schutz gegen XSS, etc.
     * @param string $value
     * @param int $mode
     * @return string
     */
    public static function escapeVal($value, $mode = false)
    {
        return htmlentities($value, ($mode ? (int) $mode : ENT_COMPAT | ENT_HTML5));
    }

    /**
     * Erzeugt verstecktes Feld mit Page-Token zur Absicherung gegen Cross-Site-Request-Forgery
     */
    public static function pageTokenField()
    {
        $tokenValue = \fpcm\classes\security::createPageToken();
        self::hiddenInput(\fpcm\classes\security::getPageTokenFieldName(), $tokenValue);
    }

    /**
     * Erzeugt Link für "Hilfe"-Button welcher Hilfe-Seite aufruft und entsprechende Kapitel öffnet
     * @param string $entry
     * @return string
     * @since FPCM 3.5
     */
    public static function printHelpLink($entry)
    {
        print \fpcm\classes\tools::getFullControllerLink('system/help', [
            'ref' => base64_encode(strtolower($entry))
        ]);
    }

    /**
     * Erzeugt einen "Hilfe"/"Information"-Fragezeichen-Button, wie in System-Optionen, System-Check, etc genutzt.
     * @param type $description Beschreibung für Tooltip
     * @param type $style
     * @param type $href Ziel-Link, false, wenn kein Link erzeugt werden soll
     * @param type $target Link in gleichem/ neuen Fenster öffnen
     * @since FPCM 3.1.6
     */
    public static function shortHelpButton($description, $style = '', $href = false, $target = '_self')
    {
        $description = self::$language->translate($description) ? self::$language->translate($description) : $description;

        $html = [];

        if ($href) $html[] = "<a href=\"{$href}\" target=\"{$target}\">";

        $html[] = "<span style=\"{$style}\" class=\"fa fa-question-circle fa-fw fpcm-ui-shorthelp\" title=\"{$description}\"></span>";

        if ($href) $html[] = "</a>";

        print implode('', $html) . PHP_EOL;
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

        print "<tr class=\"fpcm-td-spacer\"><td colspan=\"{$cols}\"></td></tr><tr><td colspan=\"6\">" . self::$language->translate('GLOBAL_NOTFOUND2') . "</td></tr>";
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

    /**
     * CSS-basierter Badge
     * @param array $params [class, value, title]
     * @since FPCM 3.6
     */
    public static function badge(array $params)
    {
        if (!isset($params['class'])) {
            $params['class'] = '';
        }

        (new helper\badge(uniqid('_badge')))
                ->setClass($params['class'])
                ->setText($params['title'])
                ->setValue($params['value']);
    }
}

?>