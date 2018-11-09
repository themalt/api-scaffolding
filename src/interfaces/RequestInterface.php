<?php

namespace CodeLibrary\Php\Api\Common\Interfaces;

use CodeLibrary\Php\Classes\ValueObjects\Url;

interface RequestInterface
{
    /**
     * This method should provide an interface for getting
     * authorization data sent with the request.
     *
     * @return RequestCredentialsInterface
     */
    public function getCredentials(): RequestCredentialsInterface;

    /**
     * This method should return the URL that was called for
     * making current request.
     *
     * @return Url
     */
    public function getUri(): Url;

    /**
     * This method should provide proper HTTP method name.
     *
     * @return string
     */
    public function getMethod(): string;

    /**
     * This method should return data sent with the respond as
     * associative array of key-value params.
     *
     * @return array
     */
    public function getData(): array;

    /**
     * This method should return name of the action to be called via API.
     *
     * @return string
     */
    public function getAction(): string;

    /**
     * This method should provide response format desired by user,
     * for example 'json' or 'xml'.
     *
     * @return string
     */
    public function getResponseFormat(): string;
}