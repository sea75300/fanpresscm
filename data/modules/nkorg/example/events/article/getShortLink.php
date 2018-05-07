<?php

namespace fpcm\modules\nkorg\example\events\article;

final class getShortLink extends \fpcm\modules\nkorg\example\events\eventBase {

    public function run()
    {
        return $this->data;
    }

}
