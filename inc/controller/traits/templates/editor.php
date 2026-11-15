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
     * File instance
     * @var null|\fpcm\model\templates\pub\file|\fpcm\model\files\templatefile
     */
    private null|\fpcm\model\templates\pub\file|\fpcm\model\files\templatefile $file;

    /**
     * File hash
     * @var string
     */
    private string $hash = '';

    /**
     * Parent folder
     * @var string
     */
    private string $parent = '';

    /**
     * Access granted?
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->templates || $this->permissions->system->drafts;
    }

    private function initFile()
    {
        $list = match ($this->parent) {
            'drafts' => new \fpcm\model\files\templatefilelist(),
            default => new \fpcm\model\templates\pub\templates(),
        };

        $this->file = $list->getByHash($this->hash);

        if (!$this->file instanceof \fpcm\model\templates\pub\file &&
            !$this->file instanceof \fpcm\model\files\templatefile
        ) {
            throw new \Exception('GLOBAL_NOTFOUND_FILE');
        }

        if (!$this->file->isWritable()) {
            throw new \Exception('FILE_NOT_WRITABLE');
        }
        
        if ($this->file instanceof \fpcm\model\files\templatefile) {
            $this->file->loadContent();
        }
    }
}
