<?php

namespace GorillaSoft\Grimlock\Core\Log\Enum;

/**
 * class LevelLog
 */
enum LevelLog: int
{
    case Debug = 100;
    case Info = 200;
    case Notice = 250;
    case Warning = 300;
    case Error = 400;
    case Critical = 500;
    case Alert = 550;
    case Emergency = 600;

}
