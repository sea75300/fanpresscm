<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\view;

    /**
     * View Helper
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm\model\view
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
        public static function init($langCode) {
            self::$language = new \fpcm\classes\language($langCode);
        }

        /**
         * Erzeugt Speichern Button
         * @param string $name Name des Buttons
         */
        public static function saveButton($name) {         
            self::button('submit', $name, 'GLOBAL_SAVE', 'fpcm-save-btn fpcm-loader');
        }

        /**
         * Erzeugt Speichern Button
         * @param string $name Name des Buttons
         */
        public static function deleteButton($name) {         
            self::button('submit', $name, 'GLOBAL_DELETE', 'fpcm-delete-btn fpcm-loader');
        }
        
        /**
         * Erzeugt Zuürcksetzen Button
         * @param string $name Name des Buttons
         */        
        public static function resetButton($name) {           
            self::button('reset', $name, 'GLOBAL_RESET', 'fpcm-save-reset');
        }        
        
        /**
         * 
         * Erzeugt Senden Button
         * @param string $name Name des Buttons
         * @param string $descr Beschreibung des Buttons
         * @param string $class CSS-Klasse
         */
        public static function submitButton($name, $descr, $class = '') {   
            self::button('submit', $name, $descr, 'fpcm-submit-button '.$class);
        }
        
        /**
         * Erzeugt Button
         * @param string $type Button-Type
         * @param string $name Name des Buttons
         * @param string $descr Beschreibung des Buttons
         * @param string $class CSS-Klasse
         */
        public static function button($type, $name, $descr, $class = '') {            
            $name  = ucfirst($name);            
            $descr = self::$language->translate($descr) ? self::$language->translate($descr) : $descr;
            
            $titleData = self::getTitleByCssClass($class, $descr);
            
            print "<button type=\"$type\" class=\"fpcm-ui-button fpcm-ui-margin-icon $class\" name=\"btn{$name}\" id=\"btn".self::cleanIdName($name)."\" $titleData>$descr</button>";
        }
        
        /**
         * Erzeugt Link-basierten Button
         * @param string $href Ziel-URL
         * @param string $descr Beschreibung
         * @param string $id Button-ID
         * @param string $class CSS-Klasse
         * @param string $target Link-Target
         */
        public static function linkButton($href, $descr, $id = '', $class = '', $target = '_self') {
            $descr = self::$language->translate($descr) ? self::$language->translate($descr) : $descr;

            if (!trim($id)) {
                $id = uniqid('lnkbtn_');
            }
            
            $titleData = self::getTitleByCssClass($class, $descr);

            print "<a href=\"$href\" class=\"fpcm-ui-button fpcm-ui-margin-icon $class\" id=\"".self::cleanIdName($id)."\" target=\"$target\" $titleData>$descr</a>\n";
        }
        
        /**
         * Erzeugt Button-Dummy
         * @param string $descr Beschreibung
         * @param string $class CSS-Klasse
         */
        public static function dummyButton($descr, $class = '') {
            $descr = self::$language->translate($descr) ? self::$language->translate($descr) : $descr;
            
            $titleData = self::getTitleByCssClass($class, $descr);
            
            print "<span class=\"fpcm-ui-button fpcm-ui-margin-icon $class\" $titleData>$descr</span>\n";
        }        
        
        /**
         * Erzeugt Link-basierte Bearbeiten-Button
         * @param string $href Ziel-URL Ziel-URL
         * @param bool $active Button ist aktiv
         * @param string $class CSS-Klasse
         */
        public static function editButton($href, $active = true, $class = '') {

            $descr = self::$language->translate('GLOBAL_EDIT');

            if (!$active) {
                print "<span class=\"fpcm-ui-button fpcm-ui-button-blank fpcm-ui-button-edit fpcm-ui-readonly\" title=\"$descr\">".$descr."</span>\n";
                return;
            }
            
            print "<a href=\"$href\" class=\"fpcm-ui-button fpcm-ui-button-blank fpcm-loader fpcm-ui-button-edit $class\" title=\"$descr\">".$descr."</a>\n";
        }        
        
        /**
         * Erzeugt Link-basierte Bearbeiten-Button
         * @param array $params Array mit Paramatern
         * @param bool $active Button ist aktiv
         * @param string $class CSS-Klasse
         * @param bool $isButton
         */
        public static function clearCacheButton(array $params, $active = true, $class = '', $isButton = false) {

            $descr = self::$language->translate('ARTICLES_CACHE_CLEAR');

            if (!$active) {
                print "<span class=\"fpcm-ui-button fpcm-ui-button-blank fpcm-loader fpcm-article-cache-clear fpcm-ui-readonly\" title=\"$descr\">".$descr."</span>\n";
                return;
            }
            
            foreach ($params as $key => &$val) {
                $val = "data-{$key}=\"{$val}\"";
            }

            $params = implode(' ', $params);

            $tag = $isButton ? 'button' : 'span';
            
            print "<{$tag} class=\"fpcm-ui-button fpcm-ui-button-blank fpcm-loader fpcm-button-recycle $class\" title=\"$descr\" {$params}>".$descr."</{$tag}>\n";
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
        public static function textInput($name, $class = '', $value = '', $readonly = false, $maxlength = 255, $placeholder = false, $wrapper = true) {

            $placeholder = self::$language->translate($placeholder) ? self::$language->translate($placeholder) : $placeholder;

            $html   = [];
            if ($wrapper) {
                $wrapperClass = is_string($wrapper) ? $wrapper : '';
                $html[] = "<div class=\"fpcm-ui-input-wrapper $wrapperClass\"><div class=\"fpcm-ui-input-wrapper-inner\">";
            }
            $html[] = "<input type=\"text\" class=\"fpcm-ui-input-text $class\" name=\"$name\" id=\"".self::cleanIdName($name)."\" value=\"".htmlentities($value, ENT_QUOTES)."\" maxlength=\"$maxlength\"";
            if ($readonly) $html[] = " readonly=\"readonly\"";
            if ($placeholder) $html[] = " placeholder=\"$placeholder\"";
            if ($placeholder && \fpcm\model\abstracts\view::isBrowser('MSIE 9.0')) {
                $html[] = " title=\"$placeholder\"";
            }
            
            $html[] = ">\n";
            if ($wrapper) {
                $html[] = "</div>\n";
                $html[] = "</div>\n";
            }
            
            print implode('', $html);
        }
        
        /**
         * Erzeugt hidden Inout-Feld
         * @param string $name Name des Buttons
         * @param string $value Wert
         */
        public static function hiddenInput($name, $value = '') {
            print "<input type=\"hidden\" name=\"$name\" id=\"".self::cleanIdName($name)."\" value=\"".htmlentities($value, ENT_QUOTES)."\">\n";
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
        public static function passwordInput($name, $class = '', $value = '', $readonly = false, $maxlength = 255, $placeholder = false, $wrapper = true) {
            $html   = [];
            if ($wrapper) {
                $wrapperClass = is_string($wrapper) ? $wrapper : '';
                $html[] = "<div class=\"fpcm-ui-input-wrapper $wrapperClass\"><div class=\"fpcm-ui-input-wrapper-inner\">";
            }
            $html[] = "<input type=\"password\" class=\"fpcm-ui-input-text $class\" name=\"$name\" id=\"".self::cleanIdName($name)."\" value=\"".htmlentities($value, ENT_QUOTES)."\" maxlength=\"$maxlength\"";
            if ($readonly) $html[] = " readonly=\"readonly\"";
            if ($placeholder) $html[] = " placeholder=\"$placeholder\"";
            if ($placeholder && \fpcm\model\abstracts\view::isBrowser('MSIE 9.0')) {
                $html[] = " title=\"$placeholder\"";
            }
            $html[] = ">\n";
            $html[] = "</div>\n";
            if ($wrapper) $html[] = "</div>\n";
            
            print implode('', $html);
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
        public static function checkbox($name, $class = '', $value = '', $descr = '', $id = '', $selected = true, $readonly = false) {
            $descr = self::$language->translate($descr) ? self::$language->translate($descr) : $descr;
            
            if (!trim($id)) {
                $id = uniqid('chkbox_');
            }
            
            $html   = [];
            $html[] = "<input type=\"checkbox\" class=\"fpcm-ui-input-checkbox $class\" name=\"$name\" id=\"".self::cleanIdName($id)."\" value=\"".htmlentities($value, ENT_QUOTES)."\"";
            if ($readonly) $html[] = " disabled=\"disabled\"";
            if ($selected) $html[] = " checked=\"checked\"";
            $html[] = "> <label for=\"".self::cleanIdName($id)."\">$descr</label>\n";
            
            print implode('', $html);
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
        public static function radio($name, $class = '', $value = '', $descr = '', $id = '', $selected = true, $readonly = false) {
            $descr = self::$language->translate($descr) ? self::$language->translate($descr) : $descr;
            
            if (!trim($id)) {
                $id = uniqid('rdbtn_');
            }
            
            $html   = [];
            $html[] = "<input type=\"radio\" class=\"fpcm-ui-input-checkbox $class\" name=\"$name\" id=\"".self::cleanIdName($id)."\" value=\"".htmlentities($value, ENT_QUOTES)."\"";
            if ($readonly) $html[] = " disabled=\"disabled\"";
            if ($selected) $html[] = " checked=\"checked\"";
            $html[] = "> <label for=\"$id\">$descr</label>\n";
            
            print implode('', $html);
        }
        
        /**
         * Erzeugt Textarea
         * @param string $name Name des Buttons
         * @param string $class CSS-Klasse
         * @param string $value Wert
         * @param bool $readonly readonly Status
         */
        public static function textArea($name, $class = '', $value = '', $readonly = false) {
            $html   = [];
            $html[] = "<textarea class=\"fpcm-ui-textarea $class\" name=\"$name\" id=\"".self::cleanIdName($name)."\"";
            if ($readonly) $html[] = " readonly=\"readonly\"";
            $html[] = ">".htmlentities($value, ENT_QUOTES)."</textarea>\n";
            
            print implode('', $html);            
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
        public static function select($name, $options, $selected = null, $firstEmpty = false, $firstEnabled = true, $readonly = false, $class = '') {
            $optionsString = '';

            if ($firstEnabled) $optionsString = ($firstEmpty) ? '<option value=""></option>' : '<option value="">'.self::$language->translate('GLOBAL_SELECT').'</option>';            
            foreach ($options as $key => $value) {
                $optionsString .= "<option value=\"".htmlentities($value, ENT_QUOTES)."\"";
                if (!is_null($selected) && $value == $selected) $optionsString .= " selected=\"selected\"";
                $optionsString .= ">".htmlentities($key, ENT_QUOTES)."</option>";
            }
            
            $id = self::cleanIdName($name);
            
            $html   = [];
            $html[] = "<select name=\"$name\" id=\"$id\" class=\"fpcm-ui-input-select $class\"";
            if ($readonly) $html[] = " disabled=\"disabled\"";
            $html[] = ">$optionsString</select>\n";
            
            print implode('', $html);            
        }   
        
        /**
         * Erzeugt gruppiertes Select-Menü
         * @param string $name Name des Buttons
         * @param array $options Array mit Optionen für das Auswahlmenü
         * @param string $selected vorausgewähltes Element
         * @param bool $readonly readonly Status
         */
        public static function selectGroup($name, $options, $selected = null, $readonly = false) {
            $optionsString = '';
            foreach ($options as $key => $value) {               
                $optionsString .= "<optgroup label=\"".htmlentities($key, ENT_QUOTES)."\">";
                $optionsString .= self::selectOptions($value, $selected, true);
                $optionsString .= "</optgroup>";
            }
            
            $id = self::cleanIdName($name);
            
            $html   = [];
            $html[] = "<select name=\"$name\" id=\"$id\" class=\"fpcm-ui-input-select\"";
            if ($readonly) $html[] = " disabled=\"disabled\"";
            $html[] = ">$optionsString</select>\n";
            
            print implode('', $html);             
            
        } 
        
        /**
         * Erzeugt Option für Select-Menü
         * @param array $options Array mit Optionen für das Auswahlmenü
         * @param string $selected vorausgewähltes Element
         * @param bool $return Werte sollen zurückgegeben werden
         * @return string
         */
        public static function selectOptions($options, $selected = null, $return = false) {
            $optionsString = '';
            
            foreach ($options as $key => $value) {
                $optionsString .= "<option value=\"".htmlentities($value, ENT_QUOTES)."\"";
                if (!is_null($selected) && $value == $selected) $optionsString .= " selected=\"selected\"";
                $optionsString .= ">".htmlentities($key, ENT_QUOTES)."</option>";
            }
            
            if ($return) return $optionsString;
            
            print $optionsString;
            
        }
        
        /**
         * Setzt bool-Wert in Text ja/nein um
         * @param bool $value
         * @param string $text
         */
        public static function boolToText($value, $text = 'GLOBAL_YES') {
            print ($value) ? '<span class="fa fa-check-square fpcm-ui-booltext-yes" title="'.self::$language->translate($text).'"></span>' : '<span class="fa fa-minus-square fpcm-ui-booltext-no" title="'.self::$language->translate('GLOBAL_NO').'"></span>';
        }
        
        /**
         * Erzeugt Ja/nein Select-Menü
         * @param string $name Name des Buttons
         * @param array $selected
         * @param bool $readonly readonly Status
         * @param string $class CSS-Klasse
         * @return string
         */
        public static function boolSelect($name, $selected, $readonly = false, $class = '') {            
            $options = array(self::$language->translate('GLOBAL_YES') => 1, self::$language->translate('GLOBAL_NO') => 0);
            return self::select($name, $options, $selected, false, false, $readonly, $class);
        }
        
        /**
         * Vier-Helper für einheitliche Ausgabe von Datumsangaben
         * @param int $timespan Zeitstempel
         * @param string $format Datumsformat, überschreibt "system_dtmask"
         * @param strig $return Datum-String zurückgeben und nicht in Ausgabe schreiben
         * @since FPCM 3.2.0
         */
        public static function dateText($timespan, $format = false, $return = false) {
            
            if (!$format) {
                $format = \fpcm\classes\baseconfig::$fpcmConfig->system_dtmask;
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
        public static function buttonsContainerClass() {
            print 'fpcm-buttons fpcm-buttons-fixed';
        }
        
        /**
         * Werte in View escapen als Schutz gegen XSS, etc.
         * @param string $value
         * @param int $mode
         * @return string
         */
        public static function escapeVal($value, $mode = false) {
            return htmlentities($value, ($mode ? (int) $mode : ENT_COMPAT | ENT_HTML5));
        }
        
        /**
         * Erzeugt verstecktes Feld mit Page-Token zur Absicherung gegen Cross-Site-Request-Forgery
         */
        public static function pageTokenField() {
            $tokenValue = \fpcm\classes\security::createPageToken();
            self::hiddenInput(\fpcm\classes\security::getPageTokenFieldName(), $tokenValue);
        }

        /**
         * Erzeugt Link für "Hilfe"-Button welcher Hilfe-Seite aufruft und entsprechende Kapitel öffnet
         * @param string $entry
         * @return string
         * @since FPCM 3.5
         */
        public static function printHelpLink($entry) {
            print \fpcm\classes\baseconfig::$rootPath.'index.php?module=system/help&ref='.base64_encode(strtolower($entry));
        }

        /**
         * Erzeugt einen "Hilfe"/"Information"-Fragezeichen-Button, wie in System-Optionen, System-Check, etc genutzt.
         * @param type $description Beschreibung für Tooltip
         * @param type $style
         * @param type $href Ziel-Link, false, wenn kein Link erzeugt werden soll
         * @param type $target Link in gleichem/ neuen Fenster öffnen
         * @since FPCM 3.1.6
         */
        public static function shortHelpButton($description, $style = '', $href = false, $target = '_self') {
            
            $description = self::$language->translate($description)
                         ? self::$language->translate($description)
                         : $description;
            
            $html   = [];
            
            if ($href) $html[] = "<a href=\"{$href}\" target=\"{$target}\">";

            $html[] = "<span style=\"{$style}\" class=\"fa fa-question-circle fa-fw fpcm-ui-shorthelp\" title=\"{$description}\"></span>";

            if ($href) $html[] = "</a>";
            
            print implode('', $html).PHP_EOL;
        }

        /**
         * Liefert "Keine Einträge gefunden"-Zeile zurück
         * @param array $data
         * @param int $cols
         * @return void
         */
        public static function notFoundContainer(array $data, $cols) {
            if (count($data)) return false;
            print "<tr class=\"fpcm-td-spacer\"><td colspan=\"{$cols}\"></td></tr><tr><td colspan=\"6\">".self::$language->translate('GLOBAL_NOTFOUND2')."</td></tr>";
        }

        /**
         * Progressbar-DIV
         * @param string $progressbarName
         * @since FPCM 3.2.0
         */
        public static function progressBar($progressbarName) {            
            if (!$progressbarName) return;
            include_once \fpcm\classes\baseconfig::$viewsDir.'components/progress.php';
        }

        /**
         * CSS-basierter Badge
         * @param array $params [class, value, title]
         * @since FPCM 3.6
         */
        public static function badge(array $params) {
            
            if (!isset($params['class'])) {
                $params['class'] = '';
            }

            $params['title']    = isset($params['title'])
                                ? 'title="'.self::$language->translate($params['title']).'"'
                                : '';

            print "<span class=\"fpcm-ui-badge {$params['class']}\" {$params['title']}>{$params['value']}</span>\n";
        }    

        /**
         * IDs aufräumen
         * @param string $text ID-String
         * @return string
         */
        private static function cleanIdName($text) {
            return trim(str_replace(array('[','(',')',']','-'), '', $text));
        }

        /**
         * Erzeugt title-Attribut anhand von Klassen-Namen
         * @param string $class
         * @param string $descr
         * @return string
         * @since FPCM 3.4
         */
        private static function getTitleByCssClass($class, $descr) {
            return strpos($class, 'fpcm-ui-button-blank') === false ? '' : 'title="'.$descr.'"';
        }
    }
?>