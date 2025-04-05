<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\articles;

/**
 * Article tweet trait
 * 
 * @package fpcm\controller\traits\articles\lists
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @deprecated 5.2.3-b4
 */
trait newteets {

    protected function getTwitterInstace(): \fpcm\model\system\twitter
    {
        return new \fpcm\model\system\twitter();    
    }

    protected function getTemplateContent() : array
    {
        $tph = [];

        $twitterTpl = new \fpcm\model\pubtemplates\tweet();
        foreach ($twitterTpl->getReplacementTranslations('TEMPLATE_ARTICLE_') as $tag => $descr) {
            $tph[] = (new \fpcm\view\helper\dropdownItem(md5($tag)))->setText($this->language->translate($descr).': '.$tag)->setValue($tag)->setData(['var' => $tag]);
        }

        return [
            'tpl' => $twitterTpl->getContent(),
            'vars' => $tph
        ];
    }
    
}
