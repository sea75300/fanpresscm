<?php
    /**
     * FanPress CM anonymize ip addresses in comments Cronjob
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\crons;
    
    /**
     * Cronjob comment author ipadress removal
     * 
     * @package fpcm\model\crons
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class anonymizeIps extends \fpcm\model\abstracts\cron {

        /**
         * Auszuführender Cron-Code
         */
        public function run() {
            
            $to   = time() - $this->getIntervalTime() * 2;
            
            $search = new \fpcm\model\comments\search();
            $search->datefrom   = 0;
            $search->dateto     = $to;
            $search->approved   = 1;
            $search->searchtype = 0;
            
            $commentList = new \fpcm\model\comments\commentList();
            $commentIds = $commentList->getCommentsBySearchCondition($search);

            if (!count($commentIds)) return true;

            foreach ($commentIds as $comment) {
                $ipaddress  = $comment->getIpaddress();
                
                if (strpos($ipaddress, '*') !== false) continue;
                
                $delim      = (strpos($ipaddress, ':') !== false ? ':' : '.');
                
                $ipaddress  = explode($delim, $ipaddress);
                
                $ipaddress[(count($ipaddress) - 1)] = '*';
                $ipaddress[(count($ipaddress) - 2)] = '*';
                
                $comment->setIpaddress(implode($delim, $ipaddress));
                
                $comment->update();
            }

            return true;
        }
        
    }
