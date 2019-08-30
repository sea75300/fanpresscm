<?php

/**
 * AJAX article list new tweets controller
 * 
 * AJAX controller for tweet creation
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\articles;

/**
 * AJAX Controller zum erzeugen von Tweets aus Artikelliste
 * 
 * @package fpcm\controller\ajax\articles
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class listnewtweets extends \fpcm\controller\abstracts\ajaxController {
    /**
     * 
     * @return array
     */
    protected function getPermissions()
    {
        return ['article' => ['add', 'edit', 'editall']];
    }

    /**
     * Artikel-Listen-objekt
     * @var \fpcm\model\articles\articlelist
     */
    protected $articleList;

    /**
     * Array mit Artikel-Objekten
     * @var array
     */
    protected $articleItems;

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $ids = $this->getRequestVar('ids', [
            \fpcm\classes\http::FILTER_STRIPTAGS,
            \fpcm\classes\http::FILTER_TRIM,
            \fpcm\classes\http::FILTER_JSON_DECODE,
            'object' => false
        ]);
        
        if ($ids === null) {
            return false;
        }

        $conditions = new \fpcm\model\articles\search();
        $conditions->ids = array_map('intval', $ids);

        $articleList = new \fpcm\model\articles\articlelist();
        $this->articleItems = $articleList->getArticlesByCondition($conditions, false);

        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $resOk = [];
        $resError = [];

        /* @var $article \fpcm\model\articles\article */
        foreach ($this->articleItems as $article) {
            
            $article->enableTweetCreation(true);
            if (!$article->createTweet()) {
                $resError[] = $article->getTitle();
                continue;
            }

            $resOk[] = $article->getTitle();
            sleep(1);
        }

        $this->returnData = array('notice' => 0, 'error' => 0);
        if (count($resOk)) {
            $this->returnData['notice'] = $this->language->translate('SAVE_SUCCESS_ARTICLENEWTWEET', array('{{titles}}' => implode(', ', $resOk)));
        }

        if (count($resError)) {
            $this->returnData['error'] = $this->language->translate('SAVE_FAILED_ARTICLENEWTWEET', array('{{titles}}' => implode(', ', $resError)));
        }

        $this->getSimpleResponse();
    }

}

?>