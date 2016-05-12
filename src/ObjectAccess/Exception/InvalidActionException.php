<?php
namespace Light\ObjectAccess\Exception;

use Szyman\Exception\MessageBuilder;

/**
 * An exception indicating that the requested action is not valid in the current context.
 * When this exception is thrown, it indicates a problem on the caller side.
 */
class InvalidActionException extends \RuntimeException
{
    use MessageBuilder;

    /**
     * Constructs a new Exception.
     * @param mixed 	$message
     */
    public function __construct($message)
    {
        $result = $this->prepareMessage(func_get_args());
        parent::__construct($result->message, $result->errorCode, $result->previousException);
    }
}
