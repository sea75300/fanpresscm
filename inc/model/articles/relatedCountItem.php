<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\articles;

/**
 * Releated article counts object
 * 
 * @package fpcm\model\articles
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.5
 */
final class relatedCountItem {

    /**
     * Article id
     * @var int
     */
    private $article_id = 0;

    /**
     * Number of comments
     * @var int
     */
    private $ccount = 0;

    /**
     * Number of privat or unapproved comments
     * @var int
     */
    private $cprivunapp = 0;

    /**
     * NUmer of shares
     * @var int
     */
    private $shares = 0;

    /**
     * Constructor
     * @param int $article_id
     * @param int $ccount
     * @param int $cprivunapp
     * @param int $shares
     */
    public function __construct(int $article_id, int $ccount, int $cprivunapp, int $shares)
    {
        $this->article_id = $article_id;
        $this->ccount = $ccount;
        $this->cprivunapp = $cprivunapp;
        $this->shares = $shares;
    }

    /**
     * Return Article id
     * @return int
     */
    public function getArticleId(): int
    {
        return $this->article_id;
    }

    /**
     * Returns number of comments
     * @return int
     */
    public function getComments(): int
    {
        return $this->ccount;
    }

    /**
     * Returns number of private or unapproved comments
     * @return int
     */
    public function getPrivateUnapprovedComments(): int
    {
        return $this->cprivunapp;
    }

    /**
     * Returns number of shares
     * @return int
     */
    public function getShares(): int
    {
        return $this->shares;
    }

}