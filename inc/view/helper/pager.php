<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Pager view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class pager extends helper {

    /**
     * Current page
     * @var int
     */
    private $currentPage = 1;

    /**
     * Numer of items on current page
     * @var int
     */
    private $currentPageItemsCount = 0;

    /**
     * Numer of items per page
     * @var int
     */
    private $itemsPerPage = 50;

    /**
     * Maximum numer of items
     * @var int
     */
    private $maxItemCount = 0;

    /**
     * Caculated numer if pages
     * @var int
     */
    private $maxPages = 0;

    /**
     * Action data for pager
     * @var int
     */
    private $actionLink = '';

    /**
     * Show button to next page
     * @var int
     */
    private $showNextButton = false;

    /**
     * Show button to previews page
     * @var int
     */
    private $showBackButton = false;

    /**
     * Konstruktor
     * @param string $actionLink
     * @param int $currentPage
     * @param int $currentPageItemsCount
     * @param int $itemsPerPage
     * @param int $maxItemCount
     */
    public function __construct($actionLink, $currentPage, $currentPageItemsCount, $itemsPerPage, $maxItemCount)
    {
        $this->actionLink = $actionLink;
        $this->currentPage = (int) $currentPage;
        $this->currentPageItemsCount = (int) $currentPageItemsCount;
        $this->itemsPerPage = (int) $itemsPerPage;
        $this->maxItemCount = (int) $maxItemCount;

        return parent::__construct('pager');
    }

    /**
     * @ignore
     * @return bool
     */
    protected function init()
    {
        $this->maxPages = ceil($this->maxItemCount / $this->itemsPerPage);
        if (!$this->maxPages) {
            $this->maxPages = 1;
        }

        if ($this->currentPage) {

            $this->showBackButton = $this->currentPage - 1;
            $this->showNextButton = $this->currentPage + 1;

            if ($this->showNextButton > $this->maxPages) {
                $this->showNextButton = false;
            }

            return true;
        }

        if (!$this->currentPage && $this->currentPageItemsCount < $this->maxItemCount && !(2 * $this->itemsPerPage >= $this->maxItemCount + $this->itemsPerPage)) {
            $this->showNextButton = 2;
            return true;
        }

        return true;
    }

    /**
     * Returns optional JavaScript vars
     * @see helper::getJsVars
     * @return array
     */
    public function getJsVars()
    {
        return [
            'currentPage' => $this->currentPage ? $this->currentPage : 1,
            'maxPages' => $this->maxPages,
            'actionLink' => $this->actionLink,
            'showBackButton' => $this->showBackButton,
            'showNextButton' => $this->showNextButton,
            'linkString' => \fpcm\classes\tools::getFullControllerLink($this->actionLink, [
                'page' => '__page__'
            ])
        ];
    }

    /**
     * Returns optional JavaScript language vars
     * @see helper::getJsLangVars
     * @return array
     */
    public function getJsLangVars()
    {
        return ['GLOBAL_PAGER'];
    }

    /**
     * Return element string
     * @see helper::getString
     * @return string
     */
    protected function getString()
    {
        $return = implode('', [
            '<div class="nav-item">' . (new linkButton('pagerBack'))->setText('GLOBAL_BACK')->setUrl('#')->setReadonly($this->showBackButton ? false : true)->setIcon('chevron-circle-left')->setIconOnly()->setClass('fpcm-ui-pager-element shadow-sm') . '</div>',
            '<div class="nav-item d-none d-sm-block">' . (new select('pageSelect'))->setOptions([])->setFirstOption(select::FIRST_OPTION_DISABLED)->setClass('fpcm-ui-pager-element shadow-sm') . '</div>',
            '<div class="nav-item">' . (new linkButton('pagerNext'))->setText('GLOBAL_NEXT')->setUrl('#')->setReadonly($this->showNextButton ? false : true)->setIcon('chevron-circle-right')->setIconOnly()->setClass('fpcm-ui-pager-element shadow-sm') . '</div>',
        ]);

        return $return;
    }

}
