<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\articles;

/**
 * Artikel category object
 * 
 * @package fpcm\model\articles
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.1.0-a1
 */
class articleCategory extends \fpcm\model\abstracts\model implements \fpcm\model\interfaces\dataset
{
    /**
     * Article id
     * @var int
     */
    protected int $article_id;

    /**
     * Category id
     * @var int
     */
    protected int $category_id;

    /**
     * Constructor
     * @param int $articleId
     * @param int $categoryId
     */
    public function __construct(int $articleId, int $categoryId)
    {
        $this->table = \fpcm\classes\database::tableArticleCategories;
        $this->article_id = $articleId;
        $this->category_id = $categoryId;
        parent::__construct(null);
    }

    /**
     * Save method
     * @return bool
     */
    public function save(): bool
    {
        $params = $this->getPreparedSaveParams();
        if (!$this->dbcon->insert($this->table, $params)) {
            return false;
        }

        $this->id = $this->dbcon->getLastInsertId();
        return $this->id ? true : false;    
    }

    /**
     * Update methode
     * @return bool
     * @ignore
     */
    public function update(): bool
    {
        return true;
    }

    /**
     * Deletes article -> category assignement
     * @return bool
     */
    public function delete()
    {
        return $this->dbcon->delete(
            $this->table,
            'article_id = ? AND category_id = ?',
            [$this->article_id, $this->category_id]
        );
    }

    /**
     * Deletes all article -> category assignement
     * @return bool
     */
    public function deleteByArticle()
    {
        return $this->dbcon->delete($this->table, 'article_id = ?', [$this->article_id]);
    }
}