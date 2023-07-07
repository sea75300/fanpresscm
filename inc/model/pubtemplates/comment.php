<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\pubtemplates;

/**
 * Public comment template file object
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
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

        $this->replacementTags = $this->events->trigger('template\parseComment', $this->replacementTags)->getData();

        $content = $this->content;

        $tags = array_merge($this->replacementInternal, $this->replacementTags);

        $links = $this->parseLinks($tags['{{text}}'], array('rel' => 'noopener'), true);

        if (isset($links[0]) && count($links[0])) {

            foreach ($links[0] as $link) {


                if (strpos($tags['{{text}}'], 'src="' . $link) !== false ||
                    strpos($tags['{{text}}'], "href='" . $link) !== false) {
                    continue;
                }

                if (substr($link, -3, 2) === '</') {
                    $link = substr($link, 0, -3);
                }

                $tags['{{text}}'] = preg_replace('/(' . addcslashes($link, '/?&=') . ')/is', "<a href=\"{$link}\">{$link}</a>", $tags['{{text}}']);
            }
        }

        foreach ($tags as $replacement => $value) {

            $splitTags = explode(':', $replacement);
            
            $values = [];
            $this->parseTag($splitTags[0], $value, $values, $replacement);
            if (!count($values)) {
                $values = $value;
            }
            
            $content = str_replace($splitTags, $values ?? [], $content);
        }

        $this->lazyReplace($content);
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

    /**
     * Parse comment link tag
     * @param mixed $value
     * @param array $return
     * @since 4.4
     */
    protected function parseMention($value, array &$return)
    {
        $return = ["<a href=\"\" id=\"$value\" class=\"fpcm-pub-mentionlink\">", '</a>'];
    }

    /**
     * Parse comment link tag
     * @param mixed $value
     * @param array $return
     * @since 4.4
     */
    protected function parseWebsite($value, array &$return)
    {
        $return = ["<a href=\"{$value}\" class=\"fpcm-pub-websitelink\">{$value}</a>"];
    }
}

?>