<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\comments;

/**
 * Comment lists controller
 *
 * @package fpcm\controller\ajax\articles\inedit
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2025, Stefan Seehafer
 * @since 5.3.0-a1
 */
class lists extends \fpcm\controller\abstracts\dataViewList {

    use \fpcm\controller\traits\comments\lists;

    /**
     * Articles list object
     * @var \fpcm\model\articles\articlelist
     */
    protected \fpcm\model\articles\articlelist $articles;

    /**
     * Comment list object
     * @var \fpcm\model\comments\commentList
     */
    protected \fpcm\model\comments\commentList $comments;

    /**
     * Show delete button
     * @var bool
     */
    protected bool $deleteButton = false;

    /**
     * Article id
     * @var int
     */
    protected int $articleId = 0;

    /**
     *
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->config->system_comments_enabled && $this->permissions->editCommentsMass();
    }

    /**
     * Controller request processing
     * @return bool
     */
    public function request(): bool
    {
        $return = parent::request();
        if (!$return) {
            return true;
        }

        $ev = $this->events->trigger('comments\prepareSearch', $this->conditions);
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event comments\prepareSearch failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return false;
        }

        $this->conditions = $ev->getData();
        return true;
    }

    /**
     * Execute filter
     * @return bool
     */
    protected function execute(): bool
    {
        if (!$this->articleId && !$this->isFilter) {
            $this->conditions->limit = [$this->itemsPerPage, $this->offset];
        }

        if ($this->articleId) {
            $this->conditions->articleid = $this->articleId;
            $this->conditions->searchtype = 0;
            $this->hasPager = false;
        }

        if (!$this->conditions->isMultiple()) {
            $this->conditions->deleted = 0;
            $this->conditions->orderby = ['createtime DESC'];
        }
        else {
            $this->conditions->modeDeleted = true;
        }

        $this->countMax = $this->comments->countCommentsByCondition(new \fpcm\model\comments\search());
        $this->items = $this->comments->getCommentsBySearchCondition($this->conditions);
        return true;
    }

    /**
     * Dataview columns
     * @return array
     */
    protected function getCols(): array
    {
        return [
            (new \fpcm\components\dataView\column('select', (new \fpcm\view\helper\checkbox('fpcm-select-all'))->setClass('fpcm-select-all')))->setSize(1)->setAlign('center'),
            (new \fpcm\components\dataView\column('button', ''))->setSize('auto')->setAlign('center'),
            (new \fpcm\components\dataView\column('name', 'COMMMENT_AUTHOR')),
            (new \fpcm\components\dataView\column('create', 'COMMMENT_CREATEDATE'))->setAlign('center'),
            (new \fpcm\components\dataView\column('change', 'GLOBAL_LASTCHANGE'))->setAlign('center'),
            (new \fpcm\components\dataView\column('metadata', ''))->setAlign('center')->setSize('auto')
        ];
    }

    /**
     * Get dataview row
     * @param comments $item
     * @param int $cid
     * @return \fpcm\components\dataView\row
     */
    protected function getRow($item, $cid): \fpcm\components\dataView\row
    {
        $buttons = (new \fpcm\view\helper\controlgroup('itemactions'.$cid));
        $this->getExtLineMenu($buttons, $item);

        return new \fpcm\components\dataView\row([
            new \fpcm\components\dataView\rowCol('select', (new \fpcm\view\helper\checkbox('ids[' . ($item->getEditPermission() ? '' : 'ro') . ']', 'chbx' . $cid))->setClass('fpcm-ui-list-checkbox')->setValue($cid)->setReadonly(!$item->getEditPermission()), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
            new \fpcm\components\dataView\rowCol('button', $buttons, 'fpcm-ui-dataview-align-center fpcm-ui-font-small', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
            new \fpcm\components\dataView\rowCol('name', sprintf('<strong>%s</strong><span class="fpcm ui-font-small d-block">%s %s</span>', $item->getName(), (new \fpcm\view\helper\icon('at'))->setText('GLOBAL_EMAIL'), $item->getEmail()), 'fpcm-ui-ellipsis'),
            new \fpcm\components\dataView\rowCol('create', new \fpcm\view\helper\dateText($item->getCreatetime()), 'fpcm-ui-ellipsis'),
            new \fpcm\components\dataView\rowCol('change', new \fpcm\view\helper\dateText($item->getChangetime() ? $item->getChangetime() : $item->getCreatetime()), 'fpcm-ui-ellipsis'),
            new \fpcm\components\dataView\rowCol('metadata', implode('', $item->getMetaDataStatusIcons()), 'fs-5', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
        ]);
    }

    /**
     * Init lists objects
     * @return void
     */
    protected function initLists(): void
    {
        $this->articles = new \fpcm\model\articles\articlelist();
        $this->comments = new \fpcm\model\comments\commentList();

        $this->deleteButton = $this->permissions?->comment?->delete;

        $aid = $this->request->fromGET('article_id', [ \fpcm\model\http\request::FILTER_CASTINT ]);
        if (!$aid) {
            return;
        }

        $this->articleId = $aid;
    }

    /**
     * Get extended line menu
     * @param \fpcm\view\helper\controlgroup $buttons
     * @param \fpcm\model\comments\comment $comment
     * @return bool
     */
    private function getExtLineMenu(
        \fpcm\view\helper\controlgroup &$buttons,
        \fpcm\model\comments\comment $comment,
    ) : bool
    {

        $extMenuOptions = [];

        $buttons->addItem( (new \fpcm\view\helper\openButton('commentfe'))->setUrlbyObject($comment)->setTarget(\fpcm\view\helper\linkButton::TARGET_NEW) );
        $buttons->addItem( (new \fpcm\view\helper\editButton('commentedit'))->setUrlbyObject($comment, '&mode=' . $this->getMode())->setClass('fpcm-ui-commentlist-link') );


        if ($this->articleId) {
            $extMenuOptions[] = (new \fpcm\view\helper\dropdownItem('article'.$comment->getId()))->setUrl( \fpcm\classes\tools::getControllerLink('articles/edit', ['id' => $comment->getArticleid()]) )->setText('COMMENTS_EDITARTICLE')->setIcon('book')->setIconOnly();
        }

        if ($comment->getEmail()) {
            $extMenuOptions[] = (new \fpcm\view\helper\dropdownItem('commentmail'.$comment->getId()))->setUrl('mailto:'.$comment->getEmail())->setIcon('envelope')->setIconOnly()->setText('GLOBAL_WRITEMAIL');
        }

        if ($this->deleteButton) {
            $extMenuOptions[] = new \fpcm\view\helper\dropdownSpacer();
            $extMenuOptions[] = (new \fpcm\view\helper\dropdownItem('ddDelete'.$comment->getId()))
                                ->setIcon('trash')
                                ->setText('GLOBAL_DELETE')
                                ->setData(['comid' => $comment->getId()]);
        }

        if (!count($extMenuOptions)) {
            return true;
        }

        $buttons->addItem((new \fpcm\view\helper\dropdown('commentbuttonsdd' . $comment->getId()))
            ->setIcon('bars')
            ->setIconOnly()
            ->setText('')
            ->setSelected('-1')
            ->setClass('d-inline-block')
            ->setOptions($extMenuOptions)
        );

        return true;
    }

    /**
     * Returns Module name
     * @return string
     */
    protected function getModul(): string
    {
        return 'comments';
    }

    /**
     * Get mode
     * @return string
     */
    public function getMode()
    {
        if ($this->articleId) {
            return self::MODE_ARTICLE;
        }

        return self::MODE_ALL;
    }

}
