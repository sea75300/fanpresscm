<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\dataView;

/**
 * Data view row column component
 *
 * @package fpcm\components\dataView
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
final class rowCol implements \JsonSerializable {

    use \fpcm\model\traits\jsonSerializeReturnObject;

    const COLTYPE_VALUE     = 1;
    const COLTYPE_ELEMENT   = 2;

    /**
     * Column name
     * @var string
     */
    protected string $name     = '';

    /**
     * Column value
     * @var mixed
     */
    protected $value    = '';

    /**
     * Column class
     * @var string
     */
    protected string $class    = '';

    /**
     * Type class
     * @var string
     */
    protected string $typeClass = '';

    /**
     * Column type, rowCol::COLTYPE_VALUE or rowCol::COLTYPE_ELEMENT
     * @var int
     */
    protected $type     = 0;

    /**
     * Constructor
     * @param string $name
     * @param type $value
     * @param string $class
     * @param type $type
     * @param string $typeClass
     */
    public function __construct(string $name, string|array|object $value = '', string $class = '', $type = self::COLTYPE_VALUE, string $typeClass = '')
    {
        if (is_object($value)) {
            $value = (string) $value;
        }
        elseif (is_array($value)) {
            $value = sprintf('<div>%s</div>', implode('', $value));
        }

        $this->name  = $name;
        $this->value = $value;
        $this->class = $class;
        $this->typeClass = $typeClass;
        $this->type  = (int) $type;
    }

    /**
     * Create not found row col item
     * @return array
     * @since 5.4.0-a1
     */
    final public static function getNotFound() : array
    {

        $icon = (new \fpcm\view\helper\icon('list-ul '))
                ->setSize('lg')
                ->setStack(true)
                ->setStackTop(true)
                ->setStack('ban fpcm-ui-important-text');

        $text = \fpcm\classes\loader::getObject('\fpcm\classes\language')->translate('GLOBAL_NOTFOUND2');

        return [
            new self(
                name: 'col',
                value: sprintf('%s %s', $icon, $text),
                type: \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT
            )
        ];
    }

}
