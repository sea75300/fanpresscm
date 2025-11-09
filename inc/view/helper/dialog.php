<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Dialog item
 *
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.2.4-b3
 */
class dialog implements \JsonSerializable {

    use \fpcm\model\traits\jsonSerializeReturnObject;

    /**
     * Dialog name
     * @var string
     */
    private string $name;

    /**
     * Dialog name
     * @var array
     */
    protected array $fields;

    /**
     * Constructor
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Set dialog fields
     * @param array $fields items implementing fpcm\view\helper\interfaces\jsDialogHelper
     * @return $this
     */
    public function setFields(array $fields)
    {
        $this->fields = $this->checkElements($fields);
        return $this;
    }

    /**
     * Get dialog name
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Checks if element implements \fpcm\view\helper\interfaces\jsDialogHelper
     * @param \fpcm\view\helper\interfaces\jsDialogHelper $o
     * @return bool
     */
    private function checkElement($o) : bool
    {
        if (!is_object($o) || !$o instanceof interfaces\jsDialogHelper) {
            trigger_error(sprintf('Element of class %s must be an instance of "fpcm\view\helper\interfaces\jsDialogHelper"!', $o::class));
            return false;
        }

        return true;
    }

    /**
     *
     * @param array $fields
     * @return array
     */
    private function checkElements(array $fields) : array
    {
        return array_filter($fields, function($o) {

            if (is_array($o)) {
                return $this->checkElements($o);
            }

            return $this->checkElement($o);
        });
    }

    /**
     * Returns Javascript language vars
     * @return array
     * @since 5.3.0-dev
     */
    public function getJsLangVars() : array
    {
        return [
            'GLOBAL_SELECT', 'GLOBAL_ADD', 'GLOBAL_REMOVE', 'SEARCH_WAITMSG',
            'ARTICLES_SEARCH', 'ARTICLE_SEARCH_START', 'ARTICLE_SEARCH_USER',
            'ARTICLE_SEARCH_LOGICNONE', 'ARTICLE_SEARCH_LOGICAND',
            'ARTICLE_SEARCH_LOGICOR', 'ARTICLE_SEARCH_LOGIC'
        ];
    }
}
