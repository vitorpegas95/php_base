<?php
require_once __DIR__ . "/../config/logConfig.php";
require_once __DIR__ . "/../config/environment.php";

class log
{
    static function info($obj)
    {
        global $infoPath;
        $string = date('Y-m-d-H-i-s') . " : " . json_encode($obj, JSON_PRETTY_PRINT) . PHP_EOL;
        $filename = $infoPath . date('Y-m-d') . ".log";
        file_put_contents($filename, $string, FILE_APPEND);
        @chmod($filename, 0777);
    }

    static function warning($obj)
    {
        global $warningPath;
        $string = date('Y-m-d-H-i-s') . " : " . json_encode($obj, JSON_PRETTY_PRINT) . PHP_EOL;
        $filename = $warningPath . date('Y-m-d') . ".log";
        file_put_contents($filename, $string, FILE_APPEND);
        @chmod($filename, 0777);
    }

    static function error($obj)
    {
        global $errorPath;
        $string = date('Y-m-d-H-i-s') . " : " . json_encode($obj, JSON_PRETTY_PRINT) . PHP_EOL;
        $filename = $errorPath . date('Y-m-d') . ".log";
        file_put_contents($filename, $string, FILE_APPEND);
        @chmod($filename, 0777);
    }

    static function cron($obj, $name = "cron")
    {
        global $cronPath;

        $string = date('Y-m-d-H-i-s') . " : " . json_encode($obj, JSON_PRETTY_PRINT) . PHP_EOL;
        $filename = $cronPath . $name . date('Y-m-d');

        if (file_exists($filename . ".log") && filesize($filename . ".log") * 0.000001 >= 100) {
            for ($fn = 1; $fn < 100; $fn++) {
                if (!file_exists($filename . "_" . $fn . ".log") || filesize($filename . "_" . $fn . ".log") * 0.000001 < 100) {
                    $filename .= "_" . $fn;
                    break;
                }
            }
        }

        $filename .= ".log";

        file_put_contents($filename, $string, FILE_APPEND);
        @chmod($filename, 0777);
    }

    static function inline($obj)
    {
        echo date("Y-m-d H:i:s");
        echo "<pre>" . print_r(json_encode($obj, JSON_PRETTY_PRINT), true) . "</pre>" . PHP_EOL;
    }

    static function debug($obj)
    {
        echo "<pre>" . print_r(json_encode($obj, JSON_PRETTY_PRINT), true) . "</pre>";
    }

    static function mtime()
    {
        return round(microtime(true) * 1000);
    }
}