<?php

namespace CodeLibrary\Php\Api\Common;

use CodeLibrary\Php\Api\Common\Interfaces\ActionInterface;
use CodeLibrary\Php\Api\Common\Interfaces\ApiInterface;
use CodeLibrary\Php\Api\Common\Interfaces\RequestCredentialsInterface;
use CodeLibrary\Php\Api\Common\Interfaces\RequestInterface;
use CodeLibrary\Php\Api\Common\Interfaces\ResponseInterface;

abstract class AbstractApi implements ApiInterface
{
    /**
     * Method should verify if provided request is valid, i.e. if credentials
     * match required ones (represented by RequestCredentialsInterface) and/or
     * if any other conditions are satisfied - like required data etc.
     *
     * @param RequestInterface $request
     * @param RequestCredentialsInterface $credentials
     * @return bool
     */
    abstract protected function validateRequest(RequestInterface $request, RequestCredentialsInterface $credentials): bool;

    /**
     * This method should provide proper interface representing request made to the API,
     * that provides request data, credentials etc.
     *
     * @return RequestInterface
     */
    abstract protected function buildRequest(): RequestInterface;

    /**
     * This method should build proper action for given request if possible.
     *
     * @param RequestInterface $request
     * @return ActionInterface
     */
    abstract protected function buildAction(RequestInterface $request): ActionInterface;

    /**
     * This method should be called to handle whatever exceptions may occur from
     * executing the action.
     *
     * @param \Exception $exception
     * @return mixed
     */
    abstract protected function handleException(\Exception $exception);

    /**
     * This method should implement what/how response should be sent to the user.
     * For example echo/print response data in json format, or handle few options
     * depending on request made by user.
     *
     * @param ResponseInterface $response
     * @param RequestInterface|null $request
     * @return mixed
     */
    abstract public function sendResponse(ResponseInterface $response, RequestInterface $request = null);
}