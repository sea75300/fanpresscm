<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\shares;

/**
 * Artikel Objekt
 *
 * @package fpcm\model\shares
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class share extends \fpcm\model\abstracts\dataset {

    /**
     * ARtciel id
     * @var int
     */
    protected $article_id = 0;

    /**
     * Share count
     * @var int
     */
    protected $sharecount = 0;

    /**
     * Share item string
     * @var int
     */
    protected $shareitem = '';

    /**
     * Timestamp of last share
     * @var int
     */
    protected $lastshare = 0;

    /**
     * Konstruktor
     * @param int $id
     */
    public function __construct($id = null)
    {
        $this->table = \fpcm\classes\database::tableShares;
        parent::__construct($id);
    }

    /**
     * Returns article id
     * @return int
     */
    public function getArticleId()
    {
        return (int) $this->article_id;
    }

    /**
     * Returns share count
     * @return int
     */
    public function getSharecount()
    {
        return (int) $this->sharecount;
    }

    /**
     * Returns share item string
     * @return int
     */
    public function getShareitem()
    {
        return $this->shareitem;
    }

    /**
     * Returns last share timestamp
     * @return int
     */
    public function getLastshare()
    {
        return (int) $this->lastshare;
    }

    /**
     * Set article id
     * @param int $articleId
     */
    public function setArticleId($articleId)
    {
        $this->article_id = (int) $articleId;
    }

    /**
     * Set share count
     * @param int $sharecount
     */
    public function setSharecount($sharecount)
    {
        $this->sharecount = (int) $sharecount;
    }

    /**
     * Set share item string
     * @param int $shareitem
     */
    public function setShareitem($shareitem)
    {
        $this->shareitem = $shareitem;
    }

    /**
     * Set last share timestamp
     * @param int $lastshare
     */
    public function setLastshare($lastshare)
    {
        $this->lastshare = (int) $lastshare;
    }

    /**
     * Save object
     * @return bool|int
     */
    public function save()
    {
        if (!shares::getRegisteredShares($this->shareitem)) {
            trigger_error('Failed to update share count for "'.$this->shareitem.'", item ist not defined. You might call event "pub\registerShares".');
            return false;
        }

        $ev = $this->events->trigger($this->getEventName('save'), $this->getPreparedSaveParams());
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event ipaddressSave failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return false;
        }

        if (!$this->dbcon->insert($this->table, $ev->getData())) {
            return false;
        }

        $this->id = $this->dbcon->getLastInsertId();
        return $this->id;
    }

    /**
     * Update object
     * @return bool
     */
    public function update()
    {
        if (!shares::getRegisteredShares($this->shareitem)) {
            trigger_error('Failed to update share count for "'.$this->shareitem.'", item ist not defined. You might call event "pub\registerShares".');
            return false;
        }

        $params = $this->getPreparedSaveParams();
        $params[] = $this->getId();

        $ev = $this->events->trigger($this->getEventName('update'), $params);
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event ipaddressUpdate failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return false;
        }

        $params = $ev->getData();
        $fields = $this->getFieldFromSaveParams($params);

        if (!$this->dbcon->update($this->table, $fields, array_values($params), 'id = ?')) {
            return false;
        }

        return true;
    }

    /**
     * Increase share count
     * @return bool
     */
    public function increase()
    {
        $this->sharecount++;
    }

    /**
     * Returns share item description
     * @return string
     */
    public function getDescription() : string
    {
        return $this->language->translate('EDITOR_SHARES_'.strtoupper($this->shareitem));
    }

    /**
     * Returns share item icon
     * @see \fpcm\model\pubtemplates\sharebuttons::getShareItemClass
     * @return string
     */
    public function getIcon() : string
    {
        $icon = \fpcm\model\pubtemplates\sharebuttons::getShareItemClass($this->shareitem);
        return (string) (new \fpcm\view\helper\icon($icon['icon'], $icon['prefix']))->setSize('2x');
    }
    
    /**
     * Returns event base
     * @return string
     */
    protected function getEventModule(): string
    {
        return 'share';
    }

}
