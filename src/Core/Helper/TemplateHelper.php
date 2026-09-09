<?php

namespace GorillaSoft\Grimlock\Core\Helper;

use GorillaSoft\Grimlock\Core\Collection\StringMap;
use GorillaSoft\Grimlock\Core\Dto\Param;

class TemplateHelper
{

    /**
     * @param string $message
     * @param StringMap|null $params
     * @param string|null $prefix
     * @return string
     */
    public static function replaceParams(string $message, ?StringMap $params = null, ?string $prefix = ':'): string
    {
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