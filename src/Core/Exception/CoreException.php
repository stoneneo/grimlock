<?php

namespace GorillaSoft\Grimlock\Core\Exception;

use Exception;
use Throwable;

/**
 * Class CoreException
 * Grimlock's own exception to handle errors
 * @package Grimlock\Exception
 * @author Rubén Darío Huamaní Ucharima
 */
class CoreException extends Exception
{

    private string $class;

    /**
     * CoreException constructor.
     * @param string $class
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $class, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->class . " : [$this->code]: $this->message\n";
    }

}
