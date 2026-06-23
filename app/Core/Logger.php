<?php

namespace App\Core;

class Logger
{
    private static ?string $log_dir = null;
    private static ?string $log_file = null;
    private const LEVELS = ['INFO', 'ERROR', 'WARNING'];

    public static function init(string $log_filename, string $log_dir): void
    {
        self::setLogDir($log_dir);
        self::setupLogFile($log_filename);
    }

    private static function setLogDir(string $log_dir = ""): void
    {
        if (empty($log_dir)) {
            self::$log_dir = dirname(__DIR__, 2) . "/var/log";
        } else {
            self::$log_dir = $log_dir;
        }

        if (!file_exists($log_dir)) {
            mkdir($log_dir, 0777, true);
        }
    }

    private static function setupLogFile(string $log_file): void {
        $timestamp = date('Y-m-d');
        $log_file = "{$timestamp}_$log_file.log";

        $log_path = self::$log_dir . DIRECTORY_SEPARATOR . $log_file;
        if (!file_exists(dirname($log_path))) {
            mkdir(dirname($log_path), 0777, true);
        }

        self::$log_file = $log_path;
    }

    private static function log(string $level, string $message, array $context = []): void {
        if (is_null(self::$log_file)) {
            return;
        }

        if (!in_array($level, self::LEVELS)) {
            $level = 'INFO';
        }

        // Build log entry
        $timestamp = date('Y-m-d H:i:s');

        file_put_contents(
            self::$log_file,
            "[{$level}] [{$timestamp}] {$message} " . json_encode($context, JSON_PRETTY_PRINT) . "\n",
            FILE_APPEND | LOCK_EX
        );
    }

    public static function info(string $message, array $context = []): void {
        self::log("INFO", $message, $context);
    }

    public static function error(string $message, array $context = []): void {
        self::log("ERROR", $message, $context);
    }

    public static function warning(string $message, array $context = []): void {
        self::log("WARNING", $message, $context);
    }
}