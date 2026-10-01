<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\templates\pub\articles;


/**
 * Twig based public frontend template
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class overview extends \fpcm\model\templates\twig
{

    use \fpcm\model\traits\shareLinks,
        \fpcm\model\templates\pub\traits\article;

    const VAR_ARTICLES = 'articles';

    const VAR_PAGER = 'pager_settings';
    
    const VAR_COMMENTS_ACTIVE = 'commentsActive';

    /**
     * vars within this template
     * @var array
     */
    private array $vars = [];

    /**
     * Pager settings
     * @var array
     */
    private array $pager = [];

    /**
     * On init event
     * @return bool
     */
    #[\Override]
    public function onInit(): bool
    {
        $this->vars = [
            self::VAR_ARTICLES => [],
        ];

        return true;
    }

    /**
     * Set pager data
     * @param int $count
     * @param int $perPage
     * @param int $current
     * @param int $next
     * @param int $previews
     * @param bool $archive
     */
    public function setPager(
        int $count,
        int $perPage,
        int $current,
        bool $archive,
        string $action
    )
    {
        $this->pager = [$count, $perPage, $current, $archive, $action];
    }

    /**
     * Assign article data
     * @param \fpcm\model\articles\article $article
     * @param \fpcm\model\users\author|null $author
     * @param \fpcm\model\users\author|null $changeUser
     * @param array $categories
     * @param int $commentCount
     * @return bool
     */
    final public function assignArticle(
        \fpcm\model\articles\article $article,
        ?\fpcm\model\users\author $author,
        ?\fpcm\model\users\author $changeUser,
        array $categories,
        int $commentCount,
    ): bool
    {
        $this->vars[self::VAR_ARTICLES][] = $this->apply($article, $author, $changeUser, $categories, $commentCount);

        return true;
    }

    /**
     * Before render event
     * @return bool
     */
    #[\Override]
    public function onBeforeRender(): bool
    {
        $this->vars[self::VAR_PAGER] = $this->pager;

        return $this->fromSystemTemplate(
            'articles.html.twig', //$this->getConfig()->articles_template_active,
            $this->vars
        );
    }

}
