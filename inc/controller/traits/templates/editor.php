<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\templates;

/**
 * New editor trait
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.4.0-a1
 */
trait editor {

    /**
     *
     * @var \fpcm\model\templates\pub\file|null
     */
    private ?\fpcm\model\templates\pub\file $file;

    /**
     * File hash
     * @var string
     */
    private string $hash = '';

    /**
     * Access granted?
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->templates || $this->permissions->system->drafts;
    }

    /**
     * 
     * @return \fpcm\model\templates\pub\file|null
     */
    private function initFile()
    {
        $this->file = (new \fpcm\model\templates\pub\templates)->getByHash($this->hash);

        if (!$this->file instanceof \fpcm\model\templates\pub\file) {
            throw new \Exception('GLOBAL_NOTFOUND_FILE');
        }

        if (!$this->file->isWritable()) {
            throw new \Exception('FILE_NOT_WRITABLE');
        }        
    }
}
