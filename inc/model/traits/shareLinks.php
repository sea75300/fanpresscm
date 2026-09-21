<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\traits;

/**
 * Share button link trait
 *
 * @package fpcm\model\traits
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2023, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.1.0-a1
 */
trait shareLinks {

    /**
     * Return share links for default plattforms
     * @param string $plattform
     * @param string $text
     * @param string $url
     * @return string
     */
    protected function getLink(string $plattform, string $text, string $url): ?string
    {

        return match ($plattform) {
            'likeButton' => sprintf('#', $url, $text),
            'facebook' => sprintf('https://www.facebook.com/sharer/sharer.php?u=%s&amp;t=%s', $url, $text),
            'twitter' => sprintf('https://twitter.com/intent/tweet?source=%s&amp;text=%s', $url, $text),
            'tumblr' => sprintf('http://www.tumblr.com/share?v=3&amp;u=%s&amp;t=%s&amp;s=', $url, $text),
            'pinterest' => sprintf('http://pinterest.com/pin/create/button/?url=%s&amp;description=%s', $url, $text),
            'reddit' => sprintf('http://www.reddit.com/submit?url=%s&amp;title=%s', $url, $text),
            'telegram' => sprintf('https://t.me/share/url?url=%s&text=%s', $url, $text),
            'whatsapp' => sprintf('whatsapp://send?text=%s: %s', $text, $url),
            'email' => sprintf('mailto:?subject=%s&amp;body=%s', $text, $url),
            default => null
        };

    }

    /**
     * Get Share Link items
     * @param int $oid
     * @param string $description
     * @param string $itemLink
     * @return array
     * @since 5.4.0-a1
     */
    protected function getShareLinkItems(
        string $description,
        string $itemLink
    ) : array
    {
        if (!$this->getConfig()->system_show_share) {
            return [];
        }

        $link = rawurlencode($itemLink);

        return [
            'likeButton' => [
                'link' => "#",
                'icon' => "default/likebutton.png",
            ],
            'facebook' => [
                'link' => $this->getLink('facebook', $description, $link),
                'icon' => "default/facebook.png",
            ],
            'twitter' => [
                'link' => $this->getLink('twitter', $description, $link),
                'icon' => "default/twitter.png",
            ],
            'tumblr' => [
                'link' => $this->getLink('tumblr', $description, $link),
                'icon' => "default/tumblr.png",
            ],
            'pinterest' => [
                'link' => $this->getLink('pinterest', $description, $link),
                'icon' => "default/pinterest.png",
            ],
            'reddit' => [
                'link' => $this->getLink('reddit', $description, $link),
                'icon' => "default/reddit.png",
            ],
            'whatsapp' => [
                'link' => $this->getLink('whatsapp', $description, $link),
                'icon' => "default/whatsapp.png",
            ],
            'telegram' => [
                'link' => $this->getLink('telegram', $description, $link),
                'icon' => "default/telegram.png",
            ],
            'email' => [
                'link' => $this->getLink('email', $description, $link),
                'icon' => "default/email.png",
            ]
        ];
    }

}
