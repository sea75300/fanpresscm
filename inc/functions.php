<?php

/**
 * Base functions
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
spl_autoload_register(function($class)
{
    if (strpos($class, 'fpcm') === false || strpos($class, 'fpcm\\modules\\') !== false) {
        return false;
    }

    $includePath = \fpcm\classes\dirs::getIncDirPath(str_replace(['fpcm\\', '\\'], ['', DIRECTORY_SEPARATOR], $class) . '.php');
    if (realpath($includePath) === false || !file_exists($includePath)) {
        return true;
    }

    require $includePath;
    return true;
});

spl_autoload_register(function($class)
{
    if (strpos($class, 'fpcm\\modules\\') === false) {
        return false;
    }

    $includePath = \fpcm\classes\dirs::getDataDirPath(
        \fpcm\classes\dirs::DATA_MODULES, str_replace(['fpcm\\modules\\', '\\'], ['', DIRECTORY_SEPARATOR], $class) . '.php'
    );

    if (realpath($includePath) === false || !file_exists($includePath)) {
        return true;
    }

    require $includePath;
    return true;
});

set_error_handler(function($ecode, $etext, $efile, $eline)
{
    $errorLog = dirname(__DIR__) . '/data/logs/phplog.txt';

    if (file_exists($errorLog) && !is_writable($errorLog)) {
        trigger_error($errorLog . ' is not writable');
        return false;
    }

    $codeMap = [
        E_USER_DEPRECATED => 'Deprecation warning',
        E_DEPRECATED => 'Deprecation warning',
        E_USER_NOTICE => 'Notice',
        E_NOTICE => 'Notice',
        E_USER_WARNING => 'Warning',
        E_WARNING => 'Warning',
    ];
    
    $typeStr = $codeMap[$ecode] ?? 'Error';
    
    $text = [
        $ecode. ' :: '.$typeStr,
        $etext,
        'in file ' .
        $efile . ', line ' .
        $eline
    ];
    
    if (defined('FPCM_DEBUG') && FPCM_DEBUG) {

        $text[] = 'Bebug Backtrace: '.PHP_EOL.implode(PHP_EOL, array_map(function(array $item) {

            if (isset($item['function'])) {
                $return[] = 'Function: '.$item['function'];
            }

            if (isset($item['class'])) {
                $return[] = 'Class: '.$item['class'];
            }

            if (isset($item['line'])) {
                $return[] = 'on line: '.$item['line'];
            }

            if (isset($item['file'])) {
                $return[] = 'in file: '.$item['file'];
            }

            return '    > '.implode(' ', $return);
        }, array_slice(debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 10), 0)) );
    }

    $LogLine = json_encode([
        'time' => date('Y-m-d H:i:s'),
        'text' => implode(PHP_EOL, $text),
        'type' => strtolower(str_replace(' ', '-', $typeStr))
    ]);

    file_put_contents($errorLog, $LogLine . PHP_EOL, FILE_APPEND);
    return true;
});

set_exception_handler(function(Throwable $ex) {

    printf("<pre>----- FATAL ERROR: Loading terminated :-S\n\nDetailed error message is available within system error log.\n%s\n%s<pre>", $ex->getMessage(), $ex->getTraceAsString());
    
    $errorLog = dirname(__DIR__) . '/data/logs/phplog.txt';

    if (file_exists($errorLog) && !is_writable($errorLog)) {
        trigger_error($errorLog . ' is not writable');
        return false;
    }

    file_put_contents($errorLog, json_encode([
        'time' => date('Y-m-d H:i:s'),
        'text' => (string) $ex,
        'type' => 'error'
    ]) . PHP_EOL, FILE_APPEND);
    return true;
    
});

/**
 * Systemlog schreiben
 * @param mixed $data
 * @return bool
 * @since 3.6
 */
function fpcmLogSystem($data)
{
    $data = is_array($data) || is_object($data) ? print_r($data, true) : $data;

    if (file_put_contents(\fpcm\classes\baseconfig::$logFiles['syslog'], json_encode(array('time' => date('Y-m-d H:i:s'), 'text' => $data)) . PHP_EOL, FILE_APPEND) === false) {
        trigger_error('Unable to write data to system log');
        return false;
    }

    return true;
}

/**
 * Datenbanklog schreiben
 * @param mixed $data
 * @return bool
 * @since 3.6
 */
function fpcmLogSql($data)
{
    $data = is_array($data) || is_object($data) ? print_r($data, true) : $data;

    if (file_put_contents(\fpcm\classes\baseconfig::$logFiles['dblog'], json_encode(array('time' => date('Y-m-d H:i:s'), 'text' => $data)) . PHP_EOL, FILE_APPEND) === false) {
        trigger_error('Unable to write data to sql log');
        return false;
    }

    return true;
}

/**
 * Paketmanagerlog schreiben
 * @param string $packageName
 * @param mixed $data
 * @return bool
 * @since 3.6
 */
function fpcmLogPackages($packageName, array $data)
{
    if (file_put_contents(\fpcm\classes\baseconfig::$logFiles['pkglog'], json_encode(array('time' => date('Y-m-d H:i:s'), 'pkgname' => $packageName, 'text' => $data)) . PHP_EOL, FILE_APPEND) === false) {
        trigger_error('Unable to write data to package manager log');
        return false;
    }

    return true;
}

/**
 * Cronlog schreiben
 * @param mixed $data
 * @return bool
 * @since 3.6
 */
function fpcmLogCron($data)
{
    $data = is_array($data) || is_object($data) ? print_r($data, true) : $data;

    if (file_put_contents(\fpcm\classes\baseconfig::$logFiles['cronlog'], json_encode(array('time' => date('Y-m-d H:i:s'), 'text' => $data)) . PHP_EOL, FILE_APPEND) === false) {
        trigger_error('Unable to write data to cronlog');
        return false;
    }

    return true;
}

/**
 * Event-Log
 * @param mixed $data
 * @return bool
 * @since 4
 */
function fpcmLogEvents($data)
{
    if (!defined('FPCM_DEBUG_EVENTS') || !FPCM_DEBUG_EVENTS) {
        return false;
    }
    
    $data = is_array($data) || is_object($data) ? print_r($data, true) : $data;

    if (file_put_contents(\fpcm\classes\baseconfig::$logFiles['eventslogs'], json_encode(['time' => date('Y-m-d H:i:s'), 'text' => $data]) . PHP_EOL, FILE_APPEND) === false) {
        trigger_error('Unable to write data to events log');
        return false;
    }

    return true;
}

/**
 * Debug-Ausgabe am Ende der Seite
 */
function fpcmDebugOutput()
{
    if (defined('FPCM_DEBUG') && !FPCM_DEBUG) {
        return false;
    }

    $html = array();
    $html[] = 'Memory usage: ' . fpcm\classes\tools::calcSize(memory_get_usage(true), 3);
    $html[] = 'Memory usage peak: ' . fpcm\classes\tools::calcSize(memory_get_peak_usage(true), 3);
    $html[] = 'Base directory: ' . \fpcm\classes\dirs::getFullDirPath('');
    $html[] = 'Execution time: ' . fpcm\classes\timer::cal() . ' sec';
    $html[] = 'Database queries: ' . \fpcm\classes\loader::getObject('\fpcm\classes\database')->getQueryCount();
    print '<div class="fpcm-debug-data row row-cols-1 row-cols-md-5 text-light bg-dark p-2 fs-6"><div class="col text-center">' . implode('</div><div class="col text-center">', $html) . '</div></div>' . PHP_EOL . PHP_EOL;
}

/**
 * FanPress CM Dump Funktion
 * @param mixed
 */
function fpcmDump()
{
    print "<pre>";

    if (func_num_args() === 1) {
        var_dump(func_get_args()[0]);
    } else {
        var_dump(func_get_args());
    }

    print "</pre>";
}
