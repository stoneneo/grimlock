<?php

namespace GorillaSoft\Grimlock\Core\Util;

use GorillaSoft\Grimlock\Core\Exception\GrimlockException;

/**
 * class GrimlockUtil
 * Class with Utilities
 * * @package Grimlock\Util
 * * @author Rubén Darío Huamaní Ucharima
 */
class GrimlockUtil
{

    /**
     * @param string $basePath
     * @param string $path
     * @return string
     * @throws GrimlockException
     */
    public static function resolvePath(string $basePath, string $path): string
    {
        if (self::isAbsolutePath($path)) {
            $real = realpath($path);
            if ($real !== false) {
                return $real;
            }
        }

        $absolute = $basePath . ltrim($path, '/');
        $real = realpath($absolute);
        if ($real === false || !is_readable($real)) {
            throw new GrimlockException(self::class, "File not readable: $path");
        }

        return $real;
    }

    /**
     * @param string $path
     * @return bool
     */
    public static  function isAbsolutePath(string $path): bool
    {
        return str_starts_with($path, '/') ||
            preg_match('/^[A-Z]:/i', $path) === 1;
    }

    /**
     * @return string
     */
    public static function getCallerPath(): string
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        return dirname($trace[1]['file']);
    }

}
