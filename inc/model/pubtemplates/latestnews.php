<?php

/**
 * Public latest news template file object
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\pubtemplates;

/**
 * Latest News Template Objekt
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
final class latestnews extends template {

    use \fpcm\model\traits\pubTemplateArticles;

    const TEMPLATE_ID = 'latestNews';

    /**
     * Comment parser
     * @var bool
     */
    protected $commentsEnabled = true;

    /**
     * Template-Platzhalter
     * @var array
     */
    protected $replacementTags = array(
        '{{headline}}' => '',
        '{{author}}' => '',
        '{{date}}' => '',
        '{{permaLink}}:{{/permaLink}}' => '',
        '{{commentLink}}:{{/commentLink}}' => ''
    );

    /**
     * Konstruktor
     * @param string $fileName
     */
    public function __construct($fileName = null)
    {
        if (!$fileName) {
            $fileName = 'latest';
        }

        parent::__construct('common' . DIRECTORY_SEPARATOR . $fileName . '.html');
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

        $content = $this->content;
        $tags = array_merge($this->replacementInternal, $this->replacementTags);
        foreach ($tags as $replacement => $value) {

            $splitTags = explode(':', $replacement);
            
            $values = [];
            $this->parseTag($splitTags[0], $value, $values, $replacement);
            if (!count($values)) {
                $values = $value;
            }

            $content = str_replace($splitTags, $values, $content);
        }

        return $content;
    }

    /**
     * Assigns template variables by object of type @see \fpcm\model\articles\article
     * @param \fpcm\model\articles\article $article
     * @param \fpcm\model\users\author $author
     * @return bool
     */
    public function assignByObject(\fpcm\model\articles\article $article, $author)
    {
        $this->setReplacementTags([
            '{{headline}}' => $article->getTitle(),
            '{{author}}' => $author instanceof \fpcm\model\users\author ? $author->getDisplayname() : $this->language->translate('GLOBAL_NOTFOUND'),
            '{{date}}' => date($this->config->system_dtmask, $article->getCreatetime()),
            '{{permaLink}}:{{/permaLink}}' => $article->getElementLink(),
            '{{commentLink}}:{{/commentLink}}' => $article->getElementLink('#comments')
        ]);
        
        return true;
    }

}

?>