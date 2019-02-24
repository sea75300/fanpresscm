<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\pubtemplates;

/**
 * Public comment template file object
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\system
 */
final class comment extends template {
    
    const TEMPLATE_ID = 'comment';

    /**
     * Template-Platzhalter
     * @var array
     */
    protected $replacementTags = array(
        '{{author}}' => '',
        '{{email}}' => '',
        '{{website}}' => '',
        '{{text}}' => '',
        '{{date}}' => '',
        '{{number}}' => '',
        '{{id}}' => '',
        '{{mentionid}}' => '',
        '{{mention}}:{{/mention}}' => ''
    );

    /**
     * Konstruktor
     * @param string $fileName Template-Datei unterhalb von data/styles/comments
     */
    public function __construct($fileName)
    {
        parent::__construct('comments' . DIRECTORY_SEPARATOR . $fileName . '.html');
        $this->allowedTags[] = '<header>';
        $this->allowedTags[] = '<article>';
        $this->allowedTags[] = '<section>';
        $this->allowedTags[] = '<footer>';
    }

    /**
     * Parst Template-Platzhalter
     * @return bool
     */
    public function parse()
    {
        if (!count($this->replacementTags) || !$this->content) {
            return false;
        }

        $this->replacementTags = $this->events->trigger('template\parseComment', $this->replacementTags);

        $content = $this->content;

        $tags = array_merge($this->replacementInternal, $this->replacementTags);

        $links = $this->parseLinks($tags['{{text}}'], array('rel' => 'noopener'), true);

        if (isset($links[0]) && count($links[0])) {

            foreach ($links[0] as $link) {

                if (strpos($tags['{{text}}'], 'href="' . $link) !== false || strpos($tags['{{text}}'], "href='" . $link) !== false) {
                    continue;
                }

                if (substr($link, -3, 2) === '</') {
                    $link = substr($link, 0, -3);
                }

                $tags['{{text}}'] = preg_replace('/(' . addcslashes($link, '/') . ')/is', "<a href=\"{$link}\">{$link}</a>", $tags['{{text}}']);
            }
        }

        foreach ($tags as $replacement => $value) {

            $replacement = explode(':', $replacement);
            $values = [];

            switch ($replacement[0]) {
                case '{{mention}}':
                    $keys = $replacement;
                    $values = array("<a href=\"\" id=\"$value\" class=\"fpcm-pub-mentionlink\">", '</a>');
                    break;
                case '{{website}}':
                    $keys = $replacement;
                    $values = array("<a href=\"{$value}\" class=\"fpcm-pub-websitelink\">{$value}</a>");
                    break;
                default:
                    $keys = $replacement;
                    $values = array($value);
                    break;
            }

            $content = str_replace($keys, $values, $content);
        }

        $this->parseMentions($content);

        return $this->parseSmileys($content);
    }

    /**
     * Mentions parsen
     * @param string $content
     * @return string
     */
    protected function parseMentions(&$content)
    {
        if (strpos($content, '@#') === false) {
            return;
        }

        $content = preg_replace("/@#([0-9])+/", "<a class=\"fpcm-pub-mentionedlink\" href=\"#c$1\">@$1</a>", $content);
    }

    /**
     * Assigns template variables by object of type @see \fpcm\model\comments\comment 
     * @param \fpcm\model\comments\comment $comment
     * @param int $index
     * @return bool
     */
    public function assignByObject(\fpcm\model\comments\comment $comment, int $index) : bool
    {
        $this->setReplacementTags([
            '{{author}}' => $comment->getName(),
            '{{email}}' => $comment->getEmail(),
            '{{website}}' => $comment->getWebsite(),
            '{{text}}' => $comment->getText(),
            '{{date}}' => date($this->config->system_dtmask, $comment->getCreatetime()),
            '{{id}}' => $comment->getId(),
            '{{number}}' => $index,
            '{{mentionid}}' => 'id="c' . $index . '"',
            '{{mention}}:{{/mention}}' => $index
        ]);
        
        return true;
    }
}

?>