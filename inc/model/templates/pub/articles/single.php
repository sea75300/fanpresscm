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
        \fpcm\model\templates\pub\traits\article,
        \fpcm\model\templates\pub\traits\comment;

    const VAR_ARTICLE = 'article';

    /**
     * vars within this template
     * @var array
     */
    private array $vars = [];

    /**
     * on init events
     * @return bool
     */
    #[\Override]
    public function onInit(): bool
    {
        $this->initCommentVars();
        return true;
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
        return $this->fromSystemTemplate(
            'article.html.twig', //$this->getConfig()->articles_template_active,
            $this->vars
        );
    }

    /**
     * Register functions
     * @param \Twig\Environment $twig
     * @return bool
     */
    #[\Override]
    public function registerFunctions(\Twig\Environment &$twig) : bool
    {

        $twig->addFunction(new \Twig\TwigFunction(
            'input_field',
            function (string $name, mixed $value = '', string $type = 'text', string $label = '', string $id = '') {
                return (new \fpcm\view\helper\textInput($name, $id))
                    ->setType($type)
                    ->setValue($value)
                    ->setText($label);
            }
        ));

        $twig->addFunction(new \Twig\TwigFunction(
            'textarea',
            function (string $name, mixed $value = '', string $label = '', string $id = '') {
                return (new \fpcm\view\helper\textarea($name, $id))
                    ->setValue($value)
                    ->setText($label);
            }
        ));

        $twig->addFunction(new \Twig\TwigFunction(
            'checkbox',
            function (string $name, mixed $selected = '', mixed $value = 1, string $label = '', string $id = '') {
                return (new \fpcm\view\helper\checkbox($name, $id))
                    ->setValue($value)
                    ->setText($label)
                    ->setSelected($selected);
            }
        ));

        return true;
    }

}
