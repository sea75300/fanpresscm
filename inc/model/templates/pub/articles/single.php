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
class single extends \fpcm\model\templates\twig
{

    use \fpcm\model\traits\shareLinks,
        common;

    const VAR_ARTICLE = 'article';
    
    const VAR_COMMENTS_ACTIVE = 'commentsActive';

    /**
     * vars within this template
     * @var array
     */
    private array $vars = [];

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
        $this->vars[self::VAR_ARTICLE] = $this->apply($article, $author, $changeUser, $categories, $commentCount);

        return true;
    }

    /**
     * Before render event
     * @return bool
     */
    #[\Override]
    public function onBeforeRender(): bool
    {
        $this->applyCommentsEnabled();
        
        return $this->fromSystemTemplate(
            'article.html.twig', //$this->getConfig()->articles_template_active,
            $this->vars
        );
    }

}
