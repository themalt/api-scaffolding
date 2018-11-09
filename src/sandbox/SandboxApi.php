<?php

namespace CodeLibrary\Php\Api\Sandbox;

use Malt\Api\BasicApiTrait;
use Malt\Api\Exceptions\UnknownActionException;
use Malt\Api\Interfaces\ActionInterface;
use Malt\Api\Interfaces\RequestCredentialsInterface;
use Malt\Api\Interfaces\RequestInterface;

final class SandboxApi extends AbstractApi
{
    /**
     * This is a trait that implements basic methods for action.
     * You can use it not to repeat same code in every action,
     * and overwrite specific methods if needed (examples below).
     * In the example you can see how you can bind methods.
     */
    use BasicApiTrait {
        handleException as protected parentHandleException;
    }

    /**
     * SandboxApi constructor.
     *
     * @param RequestCredentialsInterface $credentials
     */
    public function __construct(RequestCredentialsInterface $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * Simple object creation by namespace.
     *
     * @param RequestInterface $request
     * @return ActionInterface
     * @throws UnknownActionException
     */
    protected function buildAction(RequestInterface $request): ActionInterface
    {
        $actionName = $request->getAction();
        $fullClassName = __NAMESPACE__ . '\\Actions\\' . $actionName . 'Action';
        if (!class_exists($fullClassName)) {
            throw new UnknownActionException('Action class doeas not exist.');
        }
        return new $fullClassName();
    }

    /**
     * Sample exception handler - this method could be omitted, it's just an example
     * of how you can overwrite it and still use the code from BasicApiTrait.
     *
     * @param \Exception $exception
     * @throws \Malt\Api\Exceptions\InvalidResponseStatusCodeException
     */
    protected function handleException(\Exception $exception)
    {
        $this->parentHandleException($exception);
    }
}