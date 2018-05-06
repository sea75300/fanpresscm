<?php

namespace fpcm\modules\nkorg\example\events\logs;

final class load extends \fpcm\modules\nkorg\example\events\eventBase {

    public function run()
    {
        if ($this->data != 'nkorg-example') {
            return [];
        }

        return $this->getData();
    }

}
