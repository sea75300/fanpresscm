<?php

namespace fpcm\modules\nkorg\example\events\logs;

final class clearSystemLogs extends \fpcm\modules\nkorg\example\events\eventBase {

    public function run()
    {
        return \fpcm\events\abstracts\event::RETURNTYPE_VOID;
    }

}
