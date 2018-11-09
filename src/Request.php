<?php

namespace CodeLibrary\Php\Api\Common;

use CodeLibrary\Php\Api\Common\Interfaces\RequestCredentialsInterface;
use CodeLibrary\Php\Api\Common\Interfaces\RequestInterface;
use CodeLibrary\Php\Classes\ValueObjects\Url;

class Request implements RequestInterface
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var RequestCredentialsInterface
     */
    private $credentials;

    /**
     * @var Url
     */
    private $uri;

    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $action;

    /**
     * @var string
     */
    private $responseFormat;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->setCredentials();
        $this->setData();
        $this->setAction();
        $this->setFullUriString();
        $this->setResponseFormat();
    }

    private function setCredentials(): void
    {
        $this->credentials = new RequestCredentials($_REQUEST['username'], $_REQUEST['password']);
    }

    private function setFullUriString(): void
    {
        $urlString = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
            . '://' . $_SERVER['HTTP_HOST']
            . $_SERVER['REQUEST_URI'];
        $this->uri = Url::createFromString($urlString);
    }

    private function setData(): void
    {
        if (empty($_REQUEST['data'])) {
            $this->data = [];
            return;
        }
        if (!is_string($_REQUEST['data'])) {
            throw new \InvalidArgumentException('Request data is expected to be a serialized and urlencoded');
        }
        $this->data = @unserialize(urldecode($_REQUEST['data']));
        if ($this->data === false) {
            throw new \InvalidArgumentException('Request data is expected to be a serialized and urlencoded');
        }
    }

    private function setResponseFormat(): void
    {
        $this->responseFormat = 'json';
    }

    private function setAction(): void
    {
        $this->action = $_REQUEST['action'];
    }

    public function getUri(): Url
    {
        return $this->uri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getResponseFormat(): string
    {
        return $this->responseFormat;
    }

    public function getCredentials(): RequestCredentialsInterface
    {
        return $this->credentials;
    }
}