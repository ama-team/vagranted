<?php

namespace AmaTeam\Vagranted\Model\Exception;

use AmaTeam\Vagranted\Model\ExceptionInterface;
use Throwable;

/**
 * @author Etki <etki@etki.me>
 */
class RuntimeException extends \RuntimeException implements ExceptionInterface
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
