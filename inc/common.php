<?php

/**
 * Common inits
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
error_reporting(E_ALL);

include __DIR__ . '/classes/dirs.php';
include __DIR__ . '/classes/baseconfig.php';
include __DIR__ . '/classes/timer.php';
include __DIR__ . '/constants.php';

if (FPCM_DEBUG) {
    fpcm\classes\timer::start();
}

include __DIR__ . '/functions.php';

\fpcm\classes\dirs::init();
\fpcm\classes\baseconfig::init();
