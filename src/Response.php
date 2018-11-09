<?php

namespace CodeLibrary\Php\Api\Common;

use CodeLibrary\Php\Api\Common\Interfaces\ResponseInterface,
    CodeLibrary\Php\Api\Exception\InvalidResponseStatusCodeException;

/**
 * Basic implementation of a ResponseInterface.
 */
class Response implements ResponseInterface
{

    /**
     * Constant array with HTTP Responses.
     */
    const HTTP_RESPONSE_HEADERS = [
        200 => 'HTTP/1.1 200 OK',
        401 => 'HTTP/1.1 401 Unauthorized',
        403 => 'HTTP/1.1 403 Forbidden',
        404 => 'HTTP/1.1 404 Not Found',
        500 => 'HTTP/1.1 500 Server Error',
    ];

    /**
     * This should contain any data to be used for sending
     * response to user.
     *
     * @var array
     */
    private $data;

    /**
     * HTTP status code.
     * https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
     *
     * @var int
     */
    private $status;

    /**
     * Response constructor.
     *
     * @param int $status
     * @param array $data
     * @throws InvalidResponseStatusCodeException
     */
    public function __construct(int $status = 200, array $data = [])
    {
        if (array_key_exists($status, self::HTTP_RESPONSE_HEADERS) === true) {
            $this->status = $status;
        } else {
            throw new InvalidResponseStatusCodeException('The defined status is not valid.');
        }
        $this->data = $data;
    }

    /**
     * Get HTTP status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->status;
    }

    /**
     * Get plain data array.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Get data array encoded to JSON.
     *
     * @return string
     */
    public function getJsonData(): string
    {
        return json_encode($this->data);
    }

    public function getHttpHeader(): string
    {
        return self::HTTP_RESPONSE_HEADERS[$this->getStatusCode()];
    }
}
