<?php

namespace Takeaway\Exceptions;

/**
 * This exception is thrown whenever a request to the Takeaway backend fails.
 */
class RequestException extends \Exception
{
    /**
     * The body returned by the server, if available.
     * @var string
     */
    private $body;

    /**
     * Construct a new request exception.
     *
     * @param string $message Message describing the error.
     * @param string $body|null The body returned by the server, if available.
     */
    public function __construct($message, $body)
    {
        parent::__construct($message);

        $this->body = $body;
    }

    /**
     * Get the body returned by the server, if available.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
}
