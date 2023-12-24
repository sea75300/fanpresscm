<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\traits\articles;

/**
 * CSV import article utils
 * 
 * @package fpcm\model\traits\articles
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2023, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.2.0-a1
 */
trait csvUtils {


    /**
     * Assigns csv row to internal fields
     * @param array $csvRow
     * @return bool
     * @since 4.5-b8
     */
    public function assignCsvRow(array $csvRow): bool
    {
        $data = array_intersect_key($csvRow, array_flip($this->getFields()));
        
        if (!count($data)) {
            trigger_error('Failed to assign data, empty field set!');
            return false;
        }

        if (empty($data['title'])) {
            trigger_error('Failed to assign data, title cannot be empty!');
            return false;
        }

        if (empty($data['content'])) {
            trigger_error('Failed to assign data, content cannot be empty!');
            return false;
        }
        
        $obj = clone $this;

        $obj->setTitle($data['title']);
        $obj->setContent($data['content']);        
        
        if (!empty($csvRow['categories'])) {
            $obj->setCategories(array_map('intval', explode(';', $data['categories'])) );
        }

        $timer = false;
        if (isset($data['createtime']) && \fpcm\classes\tools::validateDateString($data['createtime'], true)) {
            $timer = strtotime($data['createtime']);
        }

        if ($timer === false) {
            $timer = time();
        }

        $obj->setCreatetime($timer);
        $obj->setCreateuser( $data['createuser'] ?? \fpcm\classes\loader::getObject('\fpcm\model\system\session')->getUserId() );

        $obj->setPinned($data['pinned'] ?? 0);
        $obj->setDraft($data['draft'] ?? 0);
        $obj->setComments($data['comments'] ?? $this->config->comments_default_active);
        $obj->setApproval($data['approval'] ?? 0);
        $obj->setImagepath($data['imagepath'] ?? '');
        $obj->setSources($data['sources'] ?? '');

        if (isset($data['archived']) && $data['archived']) {
            $obj->setArchived(1);
            $obj->setPinned(0);
            $obj->setPostponed(0);
        }

        if (!$obj->save())  {
            trigger_error('Failed to import article.'.PHP_EOL.PHP_EOL.print_r($data, true));
            return false;
        }

        unset($obj);
        return true;
    }

    /**
     * Fetch fields for mapping
     * @return array
     * @since 4.5-b8
     */
    public function getFields(): array
    {
        return [
            'TEMPLATE_ARTICLE_HEADLINE' => 'title',
            'TEMPLATE_ARTICLE_TEXT' => 'content',
            'TEMPLATE_ARTICLE_CATEGORYTEXTS' => 'categories',
            'EDITOR_STATUS_DRAFT' => 'draft',
            'EDITOR_STATUS_ARCHIVE' => 'archived',
            'EDITOR_STATUS_PINNED' => 'pinned',
            'EDITOR_STATUS_POSTPONETO' => 'postponed',
            'EDITOR_STATUS_COMMENTS' => 'comments',
            'EDITOR_STATUS_APPROVAL' => 'approval',
            'EDITOR_ARTICLEIMAGE' => 'imagepath',
            'SYSTEM_OPTIONS_NEWS_BYWRITTENTIME' => 'createtime',
            'TEMPLATE_ARTICLE_AUTHOR' => 'createuser',
            'TEMPLATE_ARTICLE_SOURCES' => 'sources',
        ];
    }

}
