<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\users;

/**
 * User settings mnodel
 * 
 * @property string $system_lang System language
 * @property string $system_dtmask Date time mask
 * @property string $system_timezone System timezone
 * @property int    $system_editor_fontsize Default editor fontsize
 * @property int    $articles_acp_limit Number of articles per page in ACP
 * @property int    $file_list_limit Nubmer of files per page
 * @property string $file_view File manager view
 * @property array  $dashboardpos Dashboard container positions
 * @property array  $dashboard_containers_disabled Disabled dashboard container
 * 
 * 
 * @package fpcm\model\user
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.1-dev
 */
class usrmeta extends \fpcm\model\abstracts\staticModel
implements  \ArrayAccess,
            \JsonSerializable,
            \fpcm\model\interfaces\hasPersistence {

    /**
     * 
     * @param string|array $data
     */
    public function __construct(string|array $data)
    {
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        $this->data = $data;
    }

    
    /**
     * Check is offset exists
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * Get offset value
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->data[$offset];
    }

    /**
     * Set value for offset
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->data[$offset] = $value;
    }

    /**
     * Unset offset => not in use!
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        trigger_error('Items of ' . self::class . ' cennot be unset as array.');
        return;
    }

    /**
     * Returns data for json serialization
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return $this->data;
    }

    /**
     * Return persitence data to store in database
     * @return int|string
     */
    public function getPersistentData(): int|string
    {
        return json_encode($this->data);
    }

}
