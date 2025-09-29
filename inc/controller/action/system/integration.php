<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
namespace fpcm\controller\action\system;

/**
 * Intergration assistant controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class integration
extends \fpcm\controller\abstracts\controller
implements \fpcm\controller\interfaces\viewByNamespace
{
    public function hasAccess()
    {
        return $this->permissions->system->options;
    }

    public function request(): bool
    {
        return parent::request();
    }

    public function process(): bool
    {
        
        $this->view->setViewVars([
            'items' => [
                'INTEGRATION_INCLUDE_API' => 'api',
                'INTEGRATION_INCLUDE_CSS' => 'styles',
                'INTEGRATION_SHOW_ARTICLES' => 'articlesInclude',
                'INTEGRATION_LATEST_NEWS' => 'latestnewsInclude',
                'INTEGRATION_PAGE_NUMBERS_TITLE' => 'titlePages',
                'INTEGRATION_SHOW_ARTICLE_TITLE' => 'titleHeadline',
                'INTEGRATION_RSS_FEED' => 'feed',
            ],
            'articleCount' => $this->config->articles_limit,
            'system_url' => rtrim($this->config->system_url, '/') . (!str_ends_with($this->config->system_url, '.php') ? '/index.php' : ''),
            'categories' => (new \fpcm\model\categories\categoryList())->getCategoriesNameListAll(),
            'templates' => (new \fpcm\model\pubtemplates\templatelist())->getArticleTemplates()
            
        ]);
        
        $this->view->addJsFilesLate(['system/integration.js']);
        
        $this->view->addTabs('integration', [
            (new \fpcm\view\helper\tabItem('integration'))->setFile($this->getViewPath())
        ]);

        $this->view->addButtons([
            (new \fpcm\view\helper\submitButton('process'))
                ->setText('GLOBAL_OK')
                ->setIcon('sync')
        ]);

        $this->view->addJsVars([
            'articlesDefault' => $this->config->articles_limit
        ]);
        
        return true;
    }
}
