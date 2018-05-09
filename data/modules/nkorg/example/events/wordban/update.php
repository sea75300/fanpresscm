<?php

namespace fpcm\modules\nkorg\example\events\wordban;

final class update extends \fpcm\modules\nkorg\example\events\eventBase {

    public function run()
    {
        $this->data['replacementtext'] = $this->data['replacementtext'].' '. microtime(true).' '.$this->key.' '. get_class($this);
        return $this->data;
    }

}
