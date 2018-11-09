<?php

namespace CodeLibrary\Php\Api\Common;

use CodeLibrary\Php\Api\Common\Interfaces\ActionInterface;
use CodeLibrary\Php\Api\Common\Interfaces\RequestCredentialsInterface;
use CodeLibrary\Php\Api\Common\Interfaces\RequestInterface;
use CodeLibrary\Php\Api\Common\Interfaces\ResponseInterface;
use CodeLibrary\Php\Api\Exception\InvalidCredentialsException;
use CodeLibrary\Php\Api\Exception\UnknownActionException;
use CodeLibrary\Php\Models\AffiliateLinkSystem\MalformedUrlStringException;

trait BasicApiTrait
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ActionInterface
     */
    protected $action;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * Credentials for verifying API access.
     *
     * @var RequestCredentialsInterface
     */
    protected $credentials;

    /**
     * Runs the API based call for current request.
     */
    public function run()
    {
        try {
            $this->request = $this->buildRequest();
            if ($this->validateRequest($this->request, $this->credentials)) {
                $this->action = $this->buildAction($this->request);
                $this->response = $this->action->execute($this->request);
                $this->sendResponse($this->response, $this->request);
            } else {
                throw new InvalidCredentialsException('Invalid credentials');
            }
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    protected function validateRequest(RequestInterface $request, RequestCredentialsInterface $credentials): bool
    {
        return $request->getCredentials()->match($credentials);
    }

    /**
     * Create basic request, no modifications - if you need some modification to the request,
     * please overwrite.
     *
     * @return RequestInterface
     */
    protected function buildRequest(): RequestInterface
    {
        return new Request();
    }

    public function sendResponse(ResponseInterface $response, RequestInterface $request = null)
    {
        header($response->getHttpHeader());
        echo json_encode($response->getData());
    }

    protected function handleException(\Exception $exception)
    {
        switch (get_class($exception)) {
            case InvalidCredentialsException::class:
                $this->sendResponse(new Response(401, [
                    'Invalid credentials.'
                ]));
                break;
            case UnknownActionException::class:
                $this->sendResponse(new Response(404, [
                    $exception->getMessage()
                ]));
                break;
            case MalformedUrlStringException::class:
                $this->sendResponse(new Response(500, [
                    'Api error: ' . $exception->getMessage()
                ]));
                break;
            default:
                $this->sendResponse(new Response(500, [
                    'API error: ' . $exception->getMessage()
                ]));
                break;
        }
    }
}