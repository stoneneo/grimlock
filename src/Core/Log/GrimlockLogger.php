<?php

namespace GorillaSoft\Grimlock\Core\Log;

use GorillaSoft\Grimlock\Core\Exception\CoreException;
use GorillaSoft\Grimlock\Core\Helper\FileHelper;
use GorillaSoft\Grimlock\Core\Log\Enum\LevelLog;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class GrimlockLogger
{
    private static ?GrimlockLogger $instance = null;

    private Logger $logger;


    /**
     * @throws CoreException
     */
    private function __construct(string $pathLog, LevelLog $level, string $appLog)
    {
        if (trim($pathLog) === '') {
            throw new CoreException(self::class, 'Path Log cannot be empty');
        }

        $this->logger = new Logger($appLog);

        $callerPath = FileHelper::getCallerPath();
        $filePath   = FileHelper::resolvePath($callerPath, $pathLog);

        $dir = dirname($filePath);

        if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
            throw new CoreException(self::class, "Failed to create log directory: $dir");
        }

        $handler = new RotatingFileHandler($filePath, 7, $level->value);
        $dateFormat = "Y-m-d H:i:s";
        $output = "[%datetime%][%level_name%]%message% %context% %extra%\n";
        $formatter = new LineFormatter($output, $dateFormat, true, true);
        $formatter->ignoreEmptyContextAndExtra();

        $handler->setFormatter($formatter);
        $this->logger->pushHandler($handler);
    }

    /**
     * @throws CoreException
     */
    public static function init(string $pathLog, LevelLog $level, string $appLog = 'Grimlock'): GrimlockLogger
    {
        if (self::$instance === null) {
            self::$instance = new GrimlockLogger($pathLog, $level, $appLog);
        }

        return self::$instance;
    }

    /**
     * @throws CoreException
     */
    public static function getInstance(): GrimlockLogger
    {
        if (self::$instance === null) {
            throw new CoreException(self::class, 'GrimlockLogger not initialized. Call GrimlockLogger::init() first.');
        }

        return self::$instance;
    }


    /**
     * @throws CoreException
     */
    private static function append(): Logger
    {
        if (self::$instance === null) {
            throw new CoreException(self::class, 'GrimlockLogger not initialized. Call GrimlockLogger::init() first.');
        }

        return self::$instance->logger;
    }

    /**
     * @throws CoreException
     */
    public static function log(LevelLog $levelLog, string $message): void
    {
        match ($levelLog) {
            LevelLog::Info  => self::append()->info($message),
            LevelLog::Notice => self::append()->notice($message),
            LevelLog::Debug => self::append()->debug($message),
            LevelLog::Warning => self::append()->warning($message),
            LevelLog::Alert => self::append()->alert($message),
            LevelLog::Critical => self::append()->critical($message),
            LevelLog::Emergency => self::append()->emergency($message),
            LevelLog::Error => self::append()->error($message),
        };
    }

}
