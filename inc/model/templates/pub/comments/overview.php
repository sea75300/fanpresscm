<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\templates\pub\comments;


/**
 * Twig based comments list frontend template
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class overview extends \fpcm\model\templates\twig
{

    use \fpcm\model\templates\pub\traits\comment;

    /**
     * vars within this template
     * @var array
     */
    private array $vars = [];

    /**
     * On init event
     * @return bool
     */
    #[\Override]
    public function onInit(): bool
    {
        $this->vars = [
            \fpcm\model\templates\variables::comments->value => [],
        ];

        return true;
    }

    /**
     * Assign comment data to comment template variables
     * @param \fpcm\model\comments\comment $comment
     * @param int $index
     * @return bool
     */
    final public function assignComment(
        \fpcm\model\comments\comment $comment,
        int $index
    ): bool
    {
        if (!$this->getConfig()->system_comments_enabled) {
            return false;
        }

        $this->vars[\fpcm\model\templates\variables::comments->value][] = $this->applyComment($comment, $index);
        return true;
    }

    /**
     * Fetch permission getter
     * @return array
     */
    #[\Override]
    public function fetchPermissions(): array
    {
        return [
            'canEdit' => $this->getPermissions()->editComments()
        ];
    }

    /**
     * Before render event
     * @return bool
     */
    #[\Override]
    public function onBeforeRender(): bool
    {
        return $this->fromSystemTemplate(
            'comments.html.twig', //$this->getConfig()->articles_template_active,
            $this->vars
        );
    }

    /**
     * Assign two excample comments to template
     * @return bool
     */
    #[\Override]
    public function asExample(): bool
    {
        $current = \fpcm\model\system\session::getInstance()->getCurrentUser();
        
        $comment1 = new \fpcm\model\comments\comment();
        $comment1->setName($current->getDisplayname());
        $comment1->setEmail($current->getEmail());
        $comment1->setWebsite((new \fpcm\model\http\request)->getHost());
        $comment1->setText('Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis.');
        $comment1->setCreatetime(time() - 3600);                
        $this->assignComment($comment1, 1);
        
        $comment2 = new \fpcm\model\comments\comment();
        $comment2->setName($current->getDisplayname());
        $comment2->setEmail($current->getEmail());
        $comment2->setWebsite((new \fpcm\model\http\request)->getHost());
        $comment2->setText('Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis?');
        $comment2->setCreatetime(time() - 1800);        
        $this->assignComment($comment2, 2);
        
        return $this->onBeforeRender();
    }

}
