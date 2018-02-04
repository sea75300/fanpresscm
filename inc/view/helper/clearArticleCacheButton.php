<?php
    /**
     * FanPress CM 4
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\view\helper;
    
    /**
     * Edit link button view helper object
     * 
     * @package fpcm\view\helper
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    final class clearArticleCacheButton extends button {

        /**
         * Optional init function
         * @return void
         */
        protected function init()
        {
            parent::init();
            $this->class    .= ' fpcm-article-cache-clear';
            $this->iconOnly  = true;
            $this->setText('ARTICLES_CACHE_CLEAR');
            $this->setIcon('recycle');
        }

        final public function setDatabyObject(\fpcm\model\articles\article $object)
        {
            $this->data      = $object->getArticleCacheParams();
            $this->readonly  = $object->getEditPermission() ? false : true;
            $this->name     .= $object->getId();
            $this->id       .= $object->getId();
            return $this;
        }

    }
?>