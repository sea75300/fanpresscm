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

}
