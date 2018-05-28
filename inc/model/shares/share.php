<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\shares;

/**
 * Artikel Objekt
 * 
 * @package fpcm\model\shares
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class share extends \fpcm\model\abstracts\dataset {

    /**
     *
     * @var int
     */
    protected $article_id = 0;

    /**
     *
     * @var int 
     */
    protected $sharecount = 0;

    /**
     *
     * @var int
     */
    protected $shareitem = '';

    /**
     *
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
     * 
     * @return int
     */
    public function getArticleId()
    {
        return (int) $this->article_id;
    }

    /**
     * 
     * @return int
     */
    public function getSharecount()
    {
        return (int) $this->sharecount;
    }

    /**
     * 
     * @return int
     */
    public function getShareitem()
    {
        return $this->shareitem;
    }

    /**
     * 
     * @return int
     */
    public function getLastshare()
    {
        return (int) $this->lastshare;
    }

    /**
     * 
     * @param int $articleId
     */
    public function setArticleId($articleId)
    {
        $this->article_id = (int) $articleId;
    }

    /**
     * 
     * @param int $sharecount
     */
    public function setSharecount($sharecount)
    {
        $this->sharecount = (int) $sharecount;
    }

    /**
     * 
     * @param int $shareitem
     */
    public function setShareitem($shareitem)
    {
        $this->shareitem = $shareitem;
    }

    /**
     * 
     * @param int $lastshare
     */
    public function setLastshare($lastshare)
    {
        $this->lastshare = (int) $lastshare;
    }

    /**
     * 
     * @return boolean|int
     */
    public function save()
    {
        if (!shares::getRegisteredShares($this->shareitem)) {
            trigger_error('Failed to update share count for "'.$this->shareitem.'", item ist not defined. You might call event "pub\registerShares".');
            return false;
        }

        if (!$this->dbcon->insert($this->table, $this->getPreparedSaveParams())) {
            return false;
        }

        $this->id = $this->dbcon->getLastInsertId();
        return $this->id;
    }

    /**
     * 
     * @return bool
     */
    public function update()
    {
        if (!shares::getRegisteredShares($this->shareitem)) {
            trigger_error('Failed to update share count for "'.$this->shareitem.'", item ist not defined. You might call event "pub\registerShares".');
            return false;
        }

        $params = $this->getPreparedSaveParams();
        $fields = array_keys($params);

        $params[] = $this->getId();
        if (!$this->dbcon->update($this->table, $fields, array_values($params), 'id = ?')) {
            return false;
        }

        return true;
    }

    /**
     * 
     * @return bool
     */
    public function increase()
    {
        $this->sharecount++;
    }

    /**
     * 
     * @return string
     */
    public function getDescription() : string
    {
        return $this->language->translate('EDITOR_SHARES_'.strtoupper($this->shareitem));
    }

    /**
     * 
     * @return string
     */
    public function getIcon() : string
    {
        $icon = \fpcm\model\pubtemplates\sharebuttons::getShareItemClass($this->shareitem);
        return (string) (new \fpcm\view\helper\icon($icon['icon'], $icon['prefix']))->setSize('2x');
    }

}
