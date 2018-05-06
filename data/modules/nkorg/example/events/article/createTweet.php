<?php

namespace fpcm\modules\nkorg\example\events\article;

final class createTweet extends \fpcm\modules\nkorg\example\events\eventBase {

    public function init()
    {
        return true;
    }

    public function run()
    {
        return $this->data;
    }

}
