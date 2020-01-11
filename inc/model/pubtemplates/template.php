<?php

/**
 * Public template file object
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\pubtemplates;

/**
 * generisches Template Objekt
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class template extends \fpcm\model\abstracts\file {

    /**
     * erlaubte Template-Tags
     * @var array
     */
    protected $allowedTags = array(
        '<div>', '<span>', '<p>', '<b>', '<strong>', '<i>', '<em>', '<u>', '<a>', '<h1>', '<h2>', '<h3>', '<h4>', '<h5>',
        '<h6>', '<img>', '<table>', '<tr>', '<td>', '<br>', '<form>', '<input>', '<button>', '<select>', '<option>',
        '<ul>', '<ol>', '<li>', '<script>', '<iframe>', '<label>'
    );

    /**
     * Template-Platzhalter
     * @var array
     */
    protected $replacementTags = [];

    /**
     * Interne Platzhalter
     * @var array
     */
    protected $replacementInternal = [];

    /**
     * Platzhalter mit Sprachbezeichner
     * @var array
     * @since FPCM 3.5.2
     */
    protected $replacementTranslated = [];

    /**
     * List of previously parsed attributes
     * @var array
     * @since FPCM 4.1
     */
    protected $replacementAttributes = [];

    /**
     * List of attributes by replacement tag
     * @var array
     * @since FPCM 4.1
     */
    protected $replacementAttributesMap = [];

    /**
     * 
     * Konstruktor
     * @param string $filename
     */
    public function __construct($filename = '')
    {
        parent::__construct($filename);
        $this->init(null);
    }

    /**
     * Returns styles base path
     * @param string $filename
     * @return string
     */
    protected function basePath($filename)
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_STYLES, $filename);
    }

    /**
     * Gibt erlaubte HTML-Tags als string zurück
     * @param string $delim
     * @return string
     */
    public function getAllowedTags($delim = '')
    {
        return implode($delim, $this->allowedTags);
    }

    /**
     * Returns raw allowed HTML tags list
     * @return array
     * @since FPCM 4.2
     */
    public function getAllowedTagsArray()
    {
        return $this->allowedTags;
    }

    /**
     * Liefert Platzhalter zurück
     * @return array
     */
    public function getReplacementTags()
    {
        return $this->replacementTags;
    }

    /**
     * Liefert interne Platzhalter zurück
     * @return array
     */
    public function getReplacementInternal()
    {
        return $this->replacementInternal;
    }

    /**
     * Returns attribute map for replacement tags
     * @return array
     */
    public function getReplacementAttributesMap() : array
    {
        return $this->replacementAttributesMap;
    }
        
    /**
     * Fügt erlaubte HTML-Tags hinzu
     * @param array $allowedTags
     */
    public function setAllowedTags(array $allowedTags)
    {
        $this->allowedTags = array_merge($this->allowedTags, $allowedTags);
    }

    /**
     * Fügt Platzhalter hinzu
     * @param array $replacementTags
     */
    public function setReplacementTags(array $replacementTags)
    {
        $this->replacementTags = $replacementTags;
    }

    /**
     * nicht verwendet
     * @return bool
     */
    public function delete()
    {
        return false;
    }

    /**
     * nicht verwendet
     * @param string $newname
     * @param int $userId
     * @return bool
     */
    public function rename($newname, $userId = false)
    {
        return false;
    }

    /**
     * Datei-Inhalt festlegen
     * @param string $content
     */
    public function setContent($content)
    {
        parent::setContent(strip_tags($content, $this->getAllowedTags()));
    }

    /**
     * Datei-Inhalt zurückgeben
     * @return string
     */
    public function getContent()
    {
        return strip_tags(parent::getContent(), $this->getAllowedTags());
    }

    /**
     * Speichert Template in Dateisystem
     * @return bool
     */
    public function save()
    {
        if (!$this->exists() || !$this->content || !$this->isWritable()) {
            return false;
        }

        $this->content = $this->events->trigger('template\save', ['file' => $this->fullpath, 'content' => $this->content])['content'];
        if (!$this->writeContent()) {
            trigger_error('Unable to update template ' . $this->fullpath);
            return false;
        }

        $this->cache->cleanup();

        return true;
    }

    /**
     * Parst Template-Platzhalter
     * @return string
     */
    public function parse()
    {
        if (!count($this->replacementTags) || !$this->content) {
            return false;
        }

        $this->replacementTags = $this->events->trigger('template\parse', $this->replacementTags);

        $tags = array_merge($this->replacementInternal, $this->replacementTags);
        return str_replace(array_keys($tags), array_values($tags), $this->content);
    }

    /**
     * Platzhalter-Übersetzungen
     * @param string $prefix
     * @return array
     * @since FPCM 3.5.2
     */
    public function getReplacementTranslations($prefix)
    {
        if (count($this->replacementTranslated)) {
            return $this->replacementTranslated;
        }

        foreach ($this->replacementTags as $key => $value) {
            $data = explode(':', strtoupper(str_replace(['{{', '}}'], '', $key)));
            $this->replacementTranslated[$key] = $this->language->translate($prefix . $data[0], [
                'pagebreakVar' => htmlspecialchars(article::PAGEBREAK_TAG)
            ]);
        }

        return $this->replacementTranslated;
    }

    /**
     * Parst Smileys in Artikeln und Kommentaren
     * @param string $content
     * @return string
     */
    protected function parseSmileys($content)
    {
        if ($this->cache->isExpired('smileyCache')) {
            $smileysList = new \fpcm\model\files\smileylist();
            $smileys = $smileysList->getDatabaseList(true);
            $this->cache->write('smileyCache', $smileys, $this->config->system_cache_timeout);
        } else {
            $smileys = $this->cache->read('smileyCache');
        }

        foreach ($smileys as $smiley) {
            $content = str_replace($smiley->getSmileyCode(), $this->parseSmileyFilePath($smiley), $content);
        }

        return $content;
    }

    /**
     * Parst Smileys
     * @param \fpcm\model\files\smiley $smiley
     * @return string
     */
    private function parseSmileyFilePath(\fpcm\model\files\smiley $smiley)
    {
        return '<img src="' . $smiley->getSmileyUrl() . '" class="fpcm-pub-smiley" ' . $smiley->getWhstring() . ' alt="">';
    }

    /**
     * Initialisiert Template-Inhalt
     * @param void $initDB
     */
    protected function init($initDB)
    {
        if (!$this->exists()) {
            return false;
        }
        
        $this->loadContent();
        $this->allowedTags = $this->events->trigger('pub\templateHtmlTags', $this->allowedTags);
    }

    /**
     * Links in Text parsen
     * @param string $content
     * @param array $attributes
     * @param bool $returnOnly
     * @return string
     */
    protected function parseLinks(&$content, array $attributes = [], $returnOnly = false)
    {
        $attrs = '';
        foreach ($attributes as $attribute => $value) {
            $attrs .= " {$attribute}=\"{$value}\"";
        }

        $regEx = '/((http|https?):\/\/\S+[^\s.,>)\]\"\'<\/])/i';

        if ($returnOnly) {
            preg_match_all($regEx, $content, $matches);
            return $matches;
        }

        $content = preg_replace($regEx, "<a href=\"$0\"{$attrs}>$0</a>", $content);

        return $content;
    }

    /**
     * Parses replacement attributes
     * @param string $var
     * @return array
     * @since FPCM 4.1
     */
    protected function parseAttributes(string $var) : array
    {
        $tagVar = '{{'.$var.'}}';
        if (!isset($this->replacementAttributesMap[$tagVar])) {
            trigger_error('No replacement attributes defined for "'.$var.'"!');
            return [];
        }
        
        if (count($this->replacementAttributes)) {
            return $this->replacementAttributes;
        }

        $matches = [];
        if (!preg_match_all("/(\{\{)({$var})(\s)(.+)(\}\})/m", $this->content, $matches)) {
            return [];
        }
        

        if (!isset($matches[4])) {
            return [];
        }

        foreach ($matches[4] as $i => $value) {

            $dest = $matches[0][$i];

            $matchesAttr = [];
            if (!preg_match_all('/('. implode('|', $this->replacementAttributesMap[$tagVar]).')(\=\")([\w\d\.\:\/\,\-\_\;\=\+\#\(\)\{\}\s]+)\"/m', $value, $matchesAttr)) {
                continue;
            }

            if (!isset($matchesAttr[1])) {
                continue;
            }

            foreach ($matchesAttr[1] as $y => $valueInner) {

                if (!trim($valueInner)) {
                    continue;
                }

                $this->replacementAttributes[$dest][$valueInner] = isset($matchesAttr[3][$y]) ? $matchesAttr[3][$y] : null;
            }

        }

        return $this->replacementAttributes;
    }
    
    protected function parseTag(string $tag, $value, array &$return, $replacement)
    {
        $tag = ucfirst(substr($tag, 2, -2));
        if (!trim($tag)) {
            return false;
        }
        
        $func = 'parse'.$tag;
        if (!method_exists($this, $func)) {
            return false;
        }
        
        $this->{$func}($value, $return, $replacement);
        return true;
    }

}

?>