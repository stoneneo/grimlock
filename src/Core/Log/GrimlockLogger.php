<?php

namespace GorillaSoft\Grimlock\Core\Log;

use GorillaSoft\Grimlock\Core\Exception\CoreException;
use GorillaSoft\Grimlock\Core\Log\Enum\LevelLog;
use GorillaSoft\Grimlock\Core\Util\AppUtil;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class GrimlockLogger
{
    private static ?GrimlockLogger $instance = null;

    private GrimlockLogger $logger;


    /**
     * @throws CoreException
     */
    private function __construct(string $pathLog, LevelLog $level, string $appLog)
    {
        if (trim($pathLog) === '') {
            throw new CoreException(self::class, 'Grimlock Path Log cannot be empty');
        }

        $this->logger = new GrimlockLogger($appLog);

        $callerPath = AppUtil::getCallerPath();
        $filePath   = AppUtil::resolvePath($callerPath, $pathLog);

        $dir = dirname($filePath);
        if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
            throw new CoreException(self::class, "Failed to create log directory: $dir");
        }

        $this->logger->pushHandler(new StreamHandler($filePath, $level->value));
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
    private static function logGuard(): GrimlockLogger
    {
        if (self::$instance === null) {
            throw new CoreException(self::class, 'GrimlockLogger not initialized.');
        }

        return self::$instance->logger;
    }

    /**
     * @throws CoreException
     */
    public static function error(string $message): void
    {
        self::logGuard()->error($message);
    }

    /**
     * @throws CoreException
     */
    public static function info(string $message): void
    {
        self::logGuard()->info($message);
    }

    /**
     * @throws CoreException
     */
    public static function debug(string $message): void
    {
        self::logGuard()->debug($message);
    }

    /**
     * @throws CoreException
     */
    public static function warn(string $message): void
    {
        self::logGuard()->warning($message);
    }

    /**
     * @throws CoreException
     */
    public static function notice(string $message): void
    {
        self::logGuard()->notice($message);
    }

    /**
     * @throws CoreException
     */
    public static function critical(string $message): void
    {
        self::logGuard()->critical($message);
    }
}
