<?php

namespace fpcm\modules\nkorg\example\events\article;

final class massEditBefore extends \fpcm\modules\nkorg\example\events\eventBase {

    public function run()
    {
        return $this->data;
    }

}
