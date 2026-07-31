<?php

namespace GorillaSoft\Grimlock\Core\Log;

use GorillaSoft\Grimlock\Core\Exception\GrimlockException;
use GorillaSoft\Grimlock\Core\Log\Enum\LevelLog;
use GorillaSoft\Grimlock\Core\Util\GrimlockUtil;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class GrimlockLog
{
    private static ?GrimlockLog $instance = null;

    private Logger $logger;


    /**
     * @throws GrimlockException
     */
    private function __construct(string $pathLog, LevelLog $level, string $appLog)
    {
        if (trim($pathLog) === '') {
            throw new GrimlockException(self::class, 'Grimlock Path Log cannot be empty');
        }

        $this->logger = new Logger($appLog);

        $callerPath = GrimlockUtil::getCallerPath();
        $filePath   = GrimlockUtil::resolvePath($callerPath, $pathLog);

        $dir = dirname($filePath);
        if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
            throw new GrimlockException(self::class, "Failed to create log directory: $dir");
        }

        $this->logger->pushHandler(new StreamHandler($filePath, $level->value));
    }

    /**
     * @throws GrimlockException
     */
    public static function init(string $pathLog, LevelLog $level, string $appLog = 'Grimlock'): GrimlockLog
    {
        if (self::$instance === null) {
            self::$instance = new GrimlockLog($pathLog, $level, $appLog);
        }

        return self::$instance;
    }

    /**
     * @throws GrimlockException
     */
    public static function getInstance(): GrimlockLog
    {
        if (self::$instance === null) {
            throw new GrimlockException(self::class, 'GrimlockLog not initialized. Call GrimlockLog::init() first.');
        }

        return self::$instance;
    }


    /**
     * @throws GrimlockException
     */
    private static function logGuard(): Logger
    {
        if (self::$instance === null) {
            throw new GrimlockException(self::class, 'GrimlockLog not initialized.');
        }

        return self::$instance->logger;
    }

    /**
     * @throws GrimlockException
     */
    public static function error(string $message): void
    {
        self::logGuard()->error($message);
    }

    /**
     * @throws GrimlockException
     */
    public static function info(string $message): void
    {
        self::logGuard()->info($message);
    }

    /**
     * @throws GrimlockException
     */
    public static function debug(string $message): void
    {
        self::logGuard()->debug($message);
    }

    /**
     * @throws GrimlockException
     */
    public static function warn(string $message): void
    {
        self::logGuard()->warning($message);
    }

    /**
     * @throws GrimlockException
     */
    public static function notice(string $message): void
    {
        self::logGuard()->notice($message);
    }

    /**
     * @throws GrimlockException
     */
    public static function critical(string $message): void
    {
        self::logGuard()->critical($message);
    }
}
