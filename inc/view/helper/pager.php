<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Pager view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
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
     * @param int $currentPage
     * @param int $currentPageItemsCount
     * @param int $itemsPerPage
     * @param int $maxItemCount
     */
    public function __construct($actionLink, $currentPage, $currentPageItemsCount, $itemsPerPage, $maxItemCount)
    {
        $this->actionLink = $actionLink;
        $this->currentPage = $currentPage;
        $this->currentPageItemsCount = $currentPageItemsCount;
        $this->itemsPerPage = $itemsPerPage;
        $this->maxItemCount = $maxItemCount;
        
        return parent::__construct('pager');
    }

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
        
        if (!$this->currentPage && $this->currentPageItemsCount < $this->maxItemCount && !(2 * $this->itemsPerPage >= $this->maxItemCount + $this->itemsPerPage) ) {
            $this->showNextButton = 2;
            return true;
        }

        return true;
    }

    /**
     * @see Return element string
     * @return string
     */
    protected function getString()
    {
        $pageOptions = [];
        for ($i=1; $i<= $this->maxPages; $i++) {
            $pageOptions[$this->language->translate('GLOBAL_PAGER', [
                '{{current}}' => $i,
                '{{total}}' => $this->maxPages])
            ] = $i;
        }

        $backLink = '#';
        if ($this->showBackButton) {
            $backLink = \fpcm\classes\tools::getFullControllerLink($this->actionLink, [
                'page' => $this->showBackButton
            ]);
        }

        $nextLink = '#';
        if ($this->showNextButton) {
            $backLink = \fpcm\classes\tools::getFullControllerLink($this->actionLink, [
                'page' => $this->showNextButton
            ]);
        }

        $return = implode('', [
            (new linkButton('back'))->setText('GLOBAL_BACK')->setUrl($backLink)->setReadonly($this->showBackButton ? false : true)->setIcon('chevron-circle-left')->setIconOnly(true)->setClass('fpcm-ui-pager-element'),
            (new select('pageSelect'))->setOptions($pageOptions)->setSelected($this->currentPage)->setFirstOption(select::FIRST_OPTION_DISABLED)->setClass('fpcm-ui-pager-element'),
            (new linkButton('next'))->setText('GLOBAL_NEXT')->setUrl($nextLink)->setReadonly($this->showNextButton ? false : true)->setIcon('chevron-circle-right')->setIconOnly(true)->setClass('fpcm-ui-pager-element')
        ]);
        
        return $return;
    }

}

?>