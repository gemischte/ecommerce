<?php

namespace App\Utils;

class Logger
{
    private static $logDir = __DIR__ . '/../../storage/logs/';

    public static function info($msg, array $context = [])
    {
        self::log('INFO', $msg, $context);
    }

    public static function warning($msg, array $context = [])
    {
        self::log('WARNING', $msg, $context);
    }
        
    public static function error ($msg, array $context = [])
    {
        self::log('ERROR', $msg, $context);
    }

    public static function debug ($msg, array $context = [])
    {
        self::log('DEBUG', $msg, $context);
    }

    private static function log($level, $msg, $context)
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        $caller = $backtrace[2] ?? $backtrace[1] ?? null;
        $sourceFile = $caller['file'] ?? 'unknown file';
        $line = $caller['line'] ?? 'unknown line';
        $times = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? " | Context: " . 
        json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        $entry_log = "[$times] [$level] $msg $contextStr [In $sourceFile:$line]" . PHP_EOL;

        $fileName = date('Y-m') . '.txt';
        $filePath = self::$logDir . $fileName;
    
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0777, true);
        }

        file_put_contents($filePath, $entry_log, FILE_APPEND | LOCK_EX);
    }
}