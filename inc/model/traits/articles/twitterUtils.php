<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\traits\articles;

/**
 * Article twitter utils
 * 
 * @package fpcm\model\traits\articles
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2023, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.2.0-a1
 */
trait twitterUtils {

    /**
     * Text für überschriebenes Tweet-Template
     * @var string
     * @since 3.3
     */
    protected $tweetOverride = false;

    /**
     * TWeet Erstellung aktivieren
     * @var bool
     * @since 3.5.2
     */
    protected $tweetCreate = null;

    /**
     * Tweet-Erstellung aktiv?
     * @return bool
     * @since 3.5.2
     */
    function tweetCreationEnabled()
    {
        return (bool) $this->tweetCreate;
    }

    /**
     * Erzeugt einen Tweet bei Twitter, wenn Verbindung aktiv und Events ausgewählt
     * @param bool $force
     * @return bool
     */
    public function createTweet($force = false)
    {
        if (!$this->config->twitter_data->isConfigured() || (!$this->config->twitter_events->create && !$this->config->twitter_events->update && !$force)) {
            return false;
        }

        if (!$force && (!$this->tweetCreate || $this->approval || $this->postponed || $this->draft || $this->deleted || $this->archived)) {
            return false;
        }

        /* @var $eventResult article */
        $eventResult = $this->events->trigger('article\createTweet', $this)->getData();

        $author = new \fpcm\model\users\author($eventResult->getCreateuser());

        $tpl = new \fpcm\model\pubtemplates\tweet();
        $tpl->setReplacementTags(array(
            '{{headline}}' => $eventResult->getTitle(),
            '{{author}}' => $author->getDisplayname(),
            '{{date}}' => date($this->config->system_dtmask, $this->getCreatetime()),
            '{{changeDate}}' => date($this->config->system_dtmask, $this->getChangetime()),
            '{{permaLink}}' => $eventResult->getElementLink(),
            '{{shortLink}}' => $eventResult->getArticleShortLink()
        ));

        if ($this->tweetOverride !== false) {
            $tpl->setContent($this->tweetOverride);
        }

        return (new \fpcm\model\system\twitter())->updateStatus($tpl->parse());
    }

    /**
     * Text für überschriebenes Tweet-Template zurückgeben
     * @return string
     * @since 3.3
     */
    function getTweetOverride()
    {
        return $this->tweetOverride;
    }

    /**
     * Text für überschriebenes Tweet-Template setzen
     * @param string $tweetOverride
     * @since 3.3
     */
    function setTweetOverride($tweetOverride)
    {
        $this->tweetOverride = $tweetOverride;
    }

    /**
     * Tweet-Erstellung aktivieren
     * @param bool $tweetCreate
     * @since 3.5.2
     */
    function enableTweetCreation($tweetCreate)
    {
        $this->tweetCreate = (bool) $tweetCreate;
    }

}
