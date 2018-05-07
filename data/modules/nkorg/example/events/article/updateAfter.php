<?php

namespace fpcm\modules\nkorg\example\events\article;

final class updateAfter extends \fpcm\modules\nkorg\example\events\eventBase {

    public function run()
    {
        return \fpcm\events\abstracts\event::RETURNTYPE_VOID;
    }

}
