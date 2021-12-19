<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\pubtemplates;

/**
 * Share button template object
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class sharebuttons extends template {

    const TEMPLATE_ID = 'shareButtons';
    
    const CACHE_MODULE = 'sharebtn';

    /**
     * Template-Platzhalter
     * @var array
     */
    protected $replacementTags = [
        '{{likeButton}}' => '',
        '{{facebook}}' => '',
        '{{twitter}}' => '',
        '{{tumblr}}' => '',
        '{{pinterest}}' => '',
        '{{reddit}}' => '',
        '{{whatsapp}}' => '',
        '{{email}}' => '',
        '{{link}}' => '',
        '{{description}}' => '',
        '{{articleId}}' => '',
        '{{credits}}' => ''
    ];
    
    /**
     * Link to share
     * @var string
     */
    protected $link;

    /**
     * Share description
     * @var string
     */
    protected $description;

    /**
     * Article id
     * @var int
     */
    protected $articleId;

    /**
     * Data stack
     * @var array
     */
    protected $stack = [];
    
    /**
     * Konstruktor
     * @param string $fileName
     */
    public function __construct($fileName = null)
    {
        if (!$fileName) {
            $fileName = 'sharebuttons';
        }

        parent::__construct('common' . DIRECTORY_SEPARATOR . $fileName . '.html');
    }
    
    /**
     * Share-Buttons parsen
     * @return string
     */
    public function parse()
    {
        if (!$this->config->system_show_share) {
            return '';
        }

        $cacheName = self::CACHE_MODULE.'/article'.$this->articleId;
        if (!$this->cache->isExpired($cacheName)) {
            return $this->cache->read($cacheName);
        }

        $content = "<!-- Start FanPress CM Share Buttons -->".PHP_EOL.$this->content.PHP_EOL."<!-- Stop FanPress CM Share Buttons -->";

        $this->stack['class'] = "fpcm-pub-sharebutton";

        if ($this->config->system_share_count) {
            $this->stack['class'] .= ' fpcm-pub-sharebutton-count';
        }
        
        foreach ($this->initTags() as $replacement => $value) {
            
            $item = trim($replacement, '{}');
            
            if (!is_array($value)) {
                $content = str_replace($replacement, $value, $content);
                continue;
            }
            
            if (!\fpcm\model\shares\shares::getRegisteredShares($item)) {
                trigger_error('Failed to parse share button "'.$replacement.'", item ist not defined. You might call event "pub\registerShares".');
                continue;
            }

            $value['icon'] = \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_SHARE, $value['icon']);
            
            if (!isset($value['target'])) {
                $value['target'] = '_blank';
            }

            $dataStr = '';
            if (count($value['data'])) {
                foreach ($value['data'] as $key => $dataValue) {
                    $dataStr .= ' data-'.$key.'="'.$dataValue.'"';
                }
            }

            if (!isset($this->stack[$replacement]['class'])) {
                $this->stack[$replacement]['class'] = $this->stack['class'].' fpcm-pub-sharebutton-'.$item;
            }
            
            $content = str_replace($replacement, "<a class=\"{$this->stack[$replacement]['class']}\" href=\"{$value['link']}\" target=\"{$value['target']}\" {$dataStr}><img {$this->getLazyLoadingImg()} src=\"{$value['icon']}\" alt=\"{$value['text']}\"></a>", $content);
        }

        $this->cache->write($cacheName, $content, $this->config->system_cache_timeout);

        return $content;
    }
    
    /**
     * 
     * Assigns template data
     * @param string $link
     * @param string $description
     * @param int $articleId
     * @return bool
     */
    public function assignData(string $link, string $description, int $articleId) : bool
    {
        $this->link = rawurlencode($link);
        $this->description = $description;
        $this->articleId = (int) $articleId;
        return true;
    }

    /**
     * Initialize tag data
     * @return array
     */
    private function initTags() : array
    {
        return $this->events->trigger('pub\parseShareButtons', array_merge($this->replacementInternal, [
            '{{likeButton}}' => [
                'link' => "#",
                'icon' => "default/likebutton.png",
                'text' => "Like-Button",
                'target' => "",
                'data' => [
                    'onclick' => 'likebutton',
                    'oid' => $this->articleId
                ]
            ],
            '{{facebook}}' => [
                'link' => "https://www.facebook.com/sharer/sharer.php?u={$this->link}&amp;t={$this->description}",
                'icon' => "default/facebook.png",
                'text' => "Facebook",
                'data' => [
                    'onclick' => 'facebook',
                    'oid' => $this->articleId
                ]
            ],
            '{{twitter}}' => [
                'link' => "https://twitter.com/intent/tweet?source={$this->link}&amp;text={$this->description}",
                'icon' => "default/twitter.png",
                'text' => "Twitter",
                'data' => [
                    'onclick' => 'twitter',
                    'oid' => $this->articleId
                ]
            ],
            '{{tumblr}}' => [
                'link' => "http://www.tumblr.com/share?v=3&amp;u={$this->link}&amp;t={$this->description}&amp;s=",
                'icon' => "default/tumblr.png",
                'text' => "Share on Tumblr",
                'data' => [
                    'onclick' => 'tumblr',
                    'oid' => $this->articleId
                ]
            ],
            '{{pinterest}}' => [
                'link' => "http://pinterest.com/pin/create/button/?url={$this->link}&amp;description={$this->description}",
                'icon' => "default/pinterest.png",
                'text' => "Pin it",
                'data' => [
                    'onclick' => 'pinterest',
                    'oid' => $this->articleId
                ]
            ],
            '{{reddit}}' => [
                'link' => "http://www.reddit.com/submit?url={$this->link}&amp;title={$this->description}",
                'icon' => "default/reddit.png",
                'text' => "Submit to Reddit",
                'data' => [
                    'onclick' => 'reddit',
                    'oid' => $this->articleId
                ]
            ],
            '{{whatsapp}}' => [
                'link' => "whatsapp://send?text={$this->description}: {$this->link}",
                'icon' => "default/whatsapp.png",
                'text' => "Share on WhatsApp",
                'data' => [
                    'action' => 'share/whatsapp/share',
                    'onclick' => 'whatsapp',
                    'oid' => $this->articleId
                ]
            ],
            '{{email}}' => [
                'link' => "mailto:?subject={$this->description}&amp;body={$this->link}",
                'icon' => "default/email.png",
                'text' => "Share via E-Mail",
                'data' => [
                    'onclick' => 'email',
                    'oid' => $this->articleId
                ]
            ],
            '{{link}}' => $this->link,
            '{{description}}' => $this->description,
            '{{articleId}}' => $this->articleId,
            '{{credits}}' => "<!-- Icon set powered by http://simplesharingbuttons.com and https://whatsappbrand.com/ -->"
        ]));
    }

    /**
     * Returns share button type mapping
     * @param string $item
     * @return array
     */
    public static function getShareItemClass($item)
    {
        $prefix = 'fab';

        if (!\fpcm\model\shares\shares::getRegisteredShares($item)) {
            trigger_error('Failed to get share icon data for "'.$item.'", item ist not defined. You might call event "pub\registerShares".');
            return [
                'icon' => $item,
                'prefix' => $prefix
            ];
        }

        switch ($item) {
            case 'email' :
                $item = 'envelope-square';
                $prefix = 'fas';
                break;
            case 'likebutton' :
                $item = 'heart';
                $prefix = 'fas';
                break;
        }

        return [
            'icon' => $item,
            'prefix' => $prefix
        ];
    }
    
}

?>