<?php

namespace fpcm\modules\nkorg\example\models;

final class container extends \fpcm\model\abstracts\dashcontainer{

    public function getContent()
    {
        return 'Ths is an example module dashboard container';
    }

    public function getHeadline()
    {
        return 'Example Container';
    }

    public function getName()
    {
        return 'example';
    }

    public function getPosition()
    {
        return self::DASHBOARD_POS_MAX;
    }

}
