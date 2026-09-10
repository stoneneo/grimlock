<?php

namespace GorillaSoft\Grimlock\Core\Helper;

use GorillaSoft\Grimlock\Core\Collection\StringMap;

class TemplateHelper
{
    /**
     * @param string|null $message
     * @param StringMap<string>|null $params
     * @param string|null $prefix
     * @return string
     */
    public static function replaceParams(?string $message, ?StringMap $params = null, ?string $prefix = ':'): string
    {
        $message ??= '';
        if ($params !== null) {
            foreach ($params as $key => $value) {
                $message = str_replace(
                    $prefix . $key,
                    $value,
                    $message
                );
            }
        }

        return $message;
    }

}
