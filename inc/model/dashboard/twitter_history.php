<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard;

/**
 * Welcome dashboard container object
 * 
 * @package fpcm\model\dashboard
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class twitter_history extends \fpcm\model\abstracts\dashcontainer implements \fpcm\model\interfaces\isAccessible {

    use \fpcm\model\traits\dashContainerCols;
    
    /**
     * Twitetr object
     * @var \fpcm\model\system\twitter
     */
    private $twitter;

    /**
     * Init internal objects
     * return void
     */
    protected function initObjects()
    {
        $this->twitter = new \fpcm\model\system\twitter();
    }

    /**
     * Returns name
     * @return string
     */
    public function getName()
    {
        return 'twitter_history';
    }

    /**
     * Returns content
     * @return string
     */
    public function getContent()
    {
        $cn = $this->getCacheName();
        
        if ($this->cache->isExpired($cn)) {
            $data = $this->twitter->fetchTimeline();           
            $this->cache->write($cn, $data);
            
        }
        else {
            $data = $this->cache->read($cn);
        }

        $data = json_decode($data, true);
        
        if (!is_array($data) || !count($data)) {
            return '';
        }

        $rows = '';

        $likeTxt = $this->language->translate('EDITOR_SHARES_LIKEBUTTON');
        $rewteetTxt = $this->language->translate('RECENT_TWEETS_REWTEETS');
        
        foreach ($data as $tweet) {
            
            $ts = strtotime($tweet['created_at']);
            
            $css = !$tweet['retweeted'] ? 'text-primary' : '';

            $col2 = '<div class="row g-0">';
            $col2 .= "  <div class=\"col {$css}\">" . \fpcm\classes\tools::parseLinks($tweet['text']) . '</div>';
            $col2 .= '</div>';

            $col2 .= '<div class="row row-cols-3 g-0 fpcm-ui-font-small">';
            $col2 .= '  <div class="col text-secondary">' . (new \fpcm\view\helper\dateText($ts)) . '</div>';

            if ($tweet['retweet_count'] && !$tweet['retweeted']) {
                $col2 .= '  <div class="col text-secondary text-center" title="'.$rewteetTxt.'">' . (new \fpcm\view\helper\icon('retweet'))->setClass('text-success') . (int) $tweet['retweet_count'] . '</div>';
            }

            if ($tweet['favorite_count']) {
                $col2 .= '  <div class="col text-secondary text-center" title="'.$likeTxt.'">' . (new \fpcm\view\helper\icon('heart'))->setClass('text-danger') . (int) $tweet['favorite_count'] . '</div>';
            }
            
            $col2 .= '</div>';
            
            $rows .= "<div class=\"row py-1\" id=\"fpcm-id-tweet-{$tweet['id_str']}\">";
            $rows .= $this->get2ColRowSmallLeftAuto( 
                (new \fpcm\view\helper\openButton("fpcm-id-tweet-open-{$tweet['id_str']}"))->setUrl('https://twitter.com/i/web/status/'.$tweet['id_str'])->setTarget('_blank')->setRel('external'),
                $col2
            );
            $rows .= '</div>';
            
        }

        return $rows;
    }
    
    public function getButton(): ?\fpcm\view\helper\linkButton
    {
        return (new \fpcm\view\helper\linkButton('twitter_history_profile'))
                ->setUrl('https://twitter.com/' . $this->twitter->getUsername())
                ->setText('PROFILE_MENU_OPENPROFILE')
                ->setTarget('_blank')
                ->setIcon('twitter', 'fab');
    }

        /**
     * Return container headline
     * @return string
     */
    public function getHeadline()
    {
        return 'RECENT_TWEETS';
    }

    /**
     * Returns container position
     * @return int
     */
    public function getPosition()
    {
        return 9;
    }

    /**
     * Container is visible/accessible
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->twitter->checkConnection();
    }

    /**
     * 
     * @param string $str
     * @return string
     */
    private function parseLinks(string $str) : string
    {
        return \fpcm\classes\tools::parseLinks($str);
    }

}
