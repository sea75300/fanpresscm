<?php

/**
 * Public article template file object
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\pubtemplates;

/**
 * Article Template Objekt
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
final class article extends template {
    
    const TEMPLATE_ID = 'article';

    const TEMPLATE_ID_SINGLE = 'articleSingle';

    /**
     * Template-Platzhalter
     * @var array
     */
    protected $replacementTags = array(
        '{{headline}}' => '',
        '{{text}}' => '',
        '{{author}}' => '',
        '{{authorEmail}}' => '',
        '{{authorInfoText}}' => '',
        '{{authorAvatar}}' => '',
        '{{date}}' => '',
        '{{changeDate}}' => '',
        '{{changeUser}}' => '',
        '{{statusPinned}}' => '',
        '{{shareButtons}}' => '',
        '{{categoryIcons}}' => '',
        '{{categoryTexts}}' => '',
        '{{commentCount}}' => '',
        '{{permaLink}}:{{/permaLink}}' => '',
        '{{commentLink}}:{{/commentLink}}' => '',
        '{{articleImage}}' => '',
        '{{sources}}' => ''
    );

    /**
     * Interne Platzhalter
     * @var array
     */
    protected $replacementInternal = array(
        '<readmore>:</readmore>'
    );

    /**
     * Kommentar-Parsner aktiv
     * @var bool
     */
    protected $commentsEnabled = true;

    /**
     * Tag-Kombinationen, die beseitigt werden müssen
     * @var array
     */
    protected $cleanups = array(
        '<p><readmore>' => '<readmore>',
        '</readmore></p>' => '</readmore>'
    );

    /**
     * Konstruktor
     * @param string $fileName Template-Datei unterhalb von data/styles/articles
     */
    public function __construct($fileName)
    {
        parent::__construct('articles' . DIRECTORY_SEPARATOR . $fileName . '.html');
    }

    /**
     * Parst Template-Platzhalter
     * @return boolean
     */
    public function parse()
    {
        if (!count($this->replacementTags) || !$this->content) {
            return false;
        }

        $this->replacementTags = $this->events->trigger('template\parseArticle', $this->replacementTags);

        $content = $this->content;
        $tags = array_merge($this->replacementInternal, $this->replacementTags);
        foreach ($tags as $replacement => $value) {

            $replacement = explode(':', $replacement);
            $values = [];

            switch ($replacement[0]) {
                case '{{permaLink}}' :
                    $keys = $replacement;
                    $values = array("<a href=\"$value\" class=\"fpcm-pub-permalink\">", '</a>');
                    break;
                case '{{commentLink}}' :
                    $keys = $replacement;
                    $values = $this->commentsEnabled ? array("<a href=\"$value\" class=\"fpcm-pub-commentlink\">", '</a>') : array('', '');
                    break;
                case '<readmore>' :
                    $keys = $replacement;
                    $values = array('<a href="#" class="fpcm-pub-readmore-link" id="' . $value . '">' . $this->language->translate('ARTICLES_PUBLIC_READMORE') . '</a><div class="fpcm-pub-readmore-text" id="fpcm-pub-readmore-text-' . $value . '">', '</div>');
                    break;
                case '{{sources}}' :
                    $keys = $replacement;
                    $this->parseLinks($value, array('rel' => 'noopener noreferrer'));
                    $values = array($value);
                    break;
                default:
                    $keys = $replacement;
                    $values = array($value);
                    break;
            }

            $content = str_replace($keys, $this->cleanup($values), $content);
        }

        return $this->parseSmileys($content);
    }

    /**
     * Kommentar-Parser aktivieren
     * @param bool $commentsEnabled
     */
    public function setCommentsEnabled($commentsEnabled)
    {
        $this->commentsEnabled = $commentsEnabled;
    }
    
    public function assignByObject(\fpcm\model\articles\article $article, array $users, array $categories, $commentCount)
    {
        $notFoundStr = $this->language->translate('GLOBAL_NOTFOUND');
        $share = new \fpcm\model\pubtemplates\sharebuttons($article->getElementLink(), $article->getTitle());
        
        if (!isset($users['author']) || !isset($users['changeUser'])) {
            trigger_error('Invalid user data, "author" or "changeUser" missing');
            return false;
        }

        $this->setReplacementTags([
            '{{headline}}' => $article->getTitle(),
            '{{text}}' => $article->getContent(),
            '{{date}}' => date($this->config->system_dtmask, $article->getCreatetime()),
            '{{statusPinned}}' => $article->getPinned() ? $this->language->translate('PUBLIC_ARTICLE_PINNED') : '',
            '{{shareButtons}}' => $share->parse(),
            '{{commentCount}}' => $this->config->system_comments_enabled && $article->getComments() ? $commentCount : 0,
            '{{author}}' => $users['author'] ? $users['author']->getDisplayname() : $notFoundStr,
            '{{authorEmail}}' => ($users['author'] ? '<a href="mailto:' . $users['author']->getEmail() . '">' . $users['author']->getDisplayname() . '</a>' : ''),
            '{{authorAvatar}}' => $users['author'] ? \fpcm\model\users\author::getAuthorImageDataOrPath($users['author'], 0) : '',
            '{{authorInfoText}}' => $users['author'] ? $users['author']->getUsrinfo() : '',
            '{{changeDate}}' => date($this->config->system_dtmask, $article->getChangetime()),
            '{{changeUser}}' => $users['changeUser'] ? $users['changeUser']->getDisplayname() : $notFoundStr,
            '{{categoryIcons}}' => implode(PHP_EOL, array_values($categories)),
            '{{categoryTexts}}' => implode(PHP_EOL, array_keys($categories)),
            '{{permaLink}}:{{/permaLink}}' => $article->getElementLink(),
            '{{commentLink}}:{{/commentLink}}' => $article->getElementLink('#comments'),
            '<readmore>:</readmore>' => $article->getId(),
            '{{articleImage}}' => $article->getArticleImage(),
            '{{sources}}' => $article->getSources()
        ]);
        
        return true;
    }

    /**
     * Tags aufräumen
     * @param string $values
     * @return string
     */
    protected function cleanup($values)
    {
        return str_replace(array_keys($this->cleanups), array_values($this->cleanups), $values);
    }

}

?>