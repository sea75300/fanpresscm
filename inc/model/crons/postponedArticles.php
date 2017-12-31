<?php
    /**
     * FanPress CM Postponed Article Cronjob
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
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
        public function run() {

            $articlesList = new \fpcm\model\articles\articlelist();
            $articleIds   = $articlesList->getArticlesPostponedIDs();
            
            if (!count($articleIds)) {
                return true;
            }

            if (!$articlesList->publishPostponedArticles($articleIds)) {
                return false;
            }

            $params = new \fpcm\model\articles\search();
            $params->ids = $articleIds;
            $articles = $articlesList->getArticlesByCondition($params, false);

            $failed = [];
            foreach ($articles as $article) {

                /**
                 * @var \fpcm\model\articles\article $article
                 */
                if ($article->createTweet(true)) {
                    continue;
                }

                $failed[] = $article->getId();
                sleep(1);

            }

            if (!count($failed)) {
                return true;
            }

            trigger_error('Failed to create tweets for postponed articles. Affected article ids: '.PHP_EOL. implode(', ', $failed));
            return true;            
        }
        
    }
