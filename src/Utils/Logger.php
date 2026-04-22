<?php

namespace App\Utils;

/**
 * @method static void debug(string $msg, array $context = [])
 * @method static void info(string $msg, array $context = [])
 * @method static void warning(string $msg, array $context = [])
 * @method static void error(string $msg, array $context = [])
 * @method static void notice(string $msg, array $context = [])
 * @method static void critical(string $msg, array $context = [])
 * @method static void alert(string $msg, array $context = [])
 * @method static void emergency(string $msg, array $context = [])
 */

class Logger
{
    private const METHOD_LEVEL_MAP = [
        'debug' => 'DEBUG',
        'info' => 'INFO',
        'warning' => 'WARNING',
        'error' => 'ERROR',
        'critical' => 'CRITICAL',
        'alert' => 'ALERT',
        'emergency' => 'EMERGENCY',
        'notice' => 'NOTICE',
    ];

    private static $logDir = __DIR__ . '/../../storage/logs/';

    public static function __callStatic($name, $arguments): void
    {
        $level = strtolower($name);
        if (!isset(self::METHOD_LEVEL_MAP[$level])) {
            throw new \InvalidArgumentException("Invalid log level: $name");
        }

        [$msg, $context] = self::normalizeArguments($arguments);

        self::log(self::METHOD_LEVEL_MAP[$level], $msg, $context);
    }

    private static function normalizeArguments(array $arguments): array
    {
        $msg = $arguments[0] ?? null;
        if (!is_string($msg) || trim($msg) === '') {
            throw new \InvalidArgumentException("Log message must be a non-empty string");
        }

        $context = $arguments[1] ?? [];
        if (!is_array($context)) {
            throw new \InvalidArgumentException("Context must be an array");
        }

        if (count($arguments) > 2) {
            $context['_extra_args'] = array_slice($arguments, 2);
        }

        return [$msg, $context];
    }

    private static function log(string $level, string $msg, array $context): void
    {
        $level = strtoupper($level);
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        $caller = $backtrace[2] ?? $backtrace[1] ?? null;
        $sourceFile = $caller['file'] ?? 'unknown file';
        $line = $caller['line'] ?? 'unknown line';
        $time = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? " | Context: " .
            json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        $entry_log = "[$time] [$level] $msg $contextStr [In $sourceFile:$line]" . PHP_EOL;

        $fileName = date('Y-m') . '.txt';
        $filePath = self::$logDir . $fileName;

        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0777, true);
        }

        file_put_contents($filePath, $entry_log, FILE_APPEND | LOCK_EX);
    }
}
