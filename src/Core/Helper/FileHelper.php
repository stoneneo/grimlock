<?php

namespace GorillaSoft\Grimlock\Core\Helper;

use GorillaSoft\Grimlock\Core\Exception\CoreException;

/**
 * class FileHelper
 * Class with Utilities
 * * @package Grimlock\Util
 * * @author Rubén Darío Huamaní Ucharima
 */
class FileHelper
{
    /**
     * @param string $basePath
     * @param string $path
     * @return string
     * @throws CoreException
     */
    public static function resolvePath(string $basePath, string $path): string
    {
        $combined = self::isAbsolutePath($path)
            ? $path
            : rtrim($basePath, '/\\') . DIRECTORY_SEPARATOR . ltrim($path, '/\\');

        $real = realpath($combined);
        if ($real !== false) {
            if (!is_readable($real)) {
                throw new CoreException(self::class, "File or directory not readable: $path");
            }
            return $real;
        }

        $dir = dirname($combined);
        $realDir = realpath($dir);

        if ($realDir === false || !is_dir($realDir) || !is_readable($realDir)) {
            throw new CoreException(self::class, "Directory not readable or does not exist: $dir");
        }

        return $realDir . DIRECTORY_SEPARATOR . basename($combined);
    }

    /**
     * @param string $path
     * @return bool
     */
    public static function isAbsolutePath(string $path): bool
    {
        return str_starts_with($path, '/') ||
            preg_match('/^[A-Z]:/i', $path) === 1;
    }

    /**
     * @return string
     * @throws CoreException
     */
    public static function getCallerPath(): string
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

        if (!isset($trace[1]['file'])) {
            throw new CoreException(self::class, 'Unable to determine caller file from backtrace');
        }

        return dirname($trace[1]['file']);
    }

}
