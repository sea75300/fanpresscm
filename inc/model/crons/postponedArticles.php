<?php

/**
 * FanPress CM Postponed Article Cronjob
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\crons;

/**
 * Cronjob postponed article publishing
 * 
 * @package fpcm\model\crons
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class postponedArticles extends \fpcm\model\abstracts\cron {

    /**
     * Auszuführender Cron-Code
     */
    public function run()
    {
        $articlesList = new \fpcm\model\articles\articlelist();
        $articleIds = $articlesList->getArticlesPostponedIDs();

        if (!count($articleIds)) {
            return true;
        }

        if (!$articlesList->publishPostponedArticles($articleIds)) {
            return false;
        }

        $params = new \fpcm\model\articles\search();
        $params->ids = $articleIds;
        $articles = $articlesList->getArticlesByCondition($params, false);

        /* @var $art \fpcm\model\articles\article */
        $this->submitMailNotification([
            (string) new \fpcm\view\helper\dateText(time()),
            implode(
                PHP_EOL,
                array_map(
                    fn ($art) => sprintf( '%s: %s (veröffentlichung am: %s)' , $art->getTitle(), $art->getEditLink(), (string) new \fpcm\view\helper\dateText($art->getCreatetime() ) ),
                    $articles
                )
            )
        ]);

        return true;
    }

}
