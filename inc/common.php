<?php

/**
 * Common inits
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
error_reporting(E_ALL);

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'spyc' . DIRECTORY_SEPARATOR . 'Spyc.php';

include __DIR__ . DIRECTORY_SEPARATOR . 'classes/dirs.php';
include __DIR__ . DIRECTORY_SEPARATOR . 'classes/baseconfig.php';
include __DIR__ . DIRECTORY_SEPARATOR . 'classes/timer.php';
include __DIR__ . DIRECTORY_SEPARATOR . 'constants.php';

if (FPCM_DEBUG) {
    fpcm\classes\timer::start();
}

include __DIR__ . DIRECTORY_SEPARATOR . 'functions.php';

\fpcm\classes\dirs::init();
\fpcm\classes\baseconfig::init();
