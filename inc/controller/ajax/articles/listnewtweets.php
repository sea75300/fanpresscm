<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\articles;

/**
 * AJAX article list new tweets controller
 * 
 * @package fpcm\controller\ajax\articles
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class listnewtweets extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

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
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->editArticles() || $this->permissions->article->add;
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->response = new \fpcm\model\http\response;
        $this->returnData = ['notice' => 0, 'error' => 0];
        
        if (!(new \fpcm\model\system\twitter())->checkRequirements()) {
            $this->response->setReturnData($this->returnData)->fetch();
        }

        $ids = $this->request->fromPOST('ids', [
            \fpcm\model\http\request::FILTER_STRIPTAGS,
            \fpcm\model\http\request::FILTER_TRIM,
            \fpcm\model\http\request::FILTER_JSON_DECODE,
            'object' => false
        ]);

        if ($ids === null) {
            $this->response->setReturnData($this->returnData)->fetch();
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

        if (count($resOk)) {
            $this->returnData['notice'] = $this->language->translate('SAVE_SUCCESS_ARTICLENEWTWEET', array('{{titles}}' => implode(', ', $resOk)));
        }

        if (count($resError)) {
            $this->returnData['error'] = $this->language->translate('SAVE_FAILED_ARTICLENEWTWEET', array('{{titles}}' => implode(', ', $resError)));
        }

        $this->response->setReturnData($this->returnData)->fetch();
        
    }

}

?>