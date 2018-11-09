<?php

namespace Malt\Api\Sandbox\Actions;
use Malt\Api\AbstractAction;
use Malt\Api\Interfaces\RequestInterface;
use Malt\Api\Interfaces\ResponseInterface;
use Malt\Api\Response;

/**
 * This class was created both as a showcase of
 * API architecture and to write test for sample
 * implementation of abstract methods.
 *
 * @package CodeLibrary\Php\Api\Sandbox\Actions
 */
final class GetShitDoneAction extends AbstractAction
{
    /**
     * Sample array for storing data. This is just an example,
     * you can create as many fields as you need in order to
     * preform given action.
     *
     * @var array
     */
    private $result;

    /**
     * The magic happens here!
     * Do whatever is needed to be done.
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Malt\Api\Exceptions\InvalidResponseStatusCodeException
     */
    public function execute(RequestInterface $request): ResponseInterface
    {
        $this->result = [];
        foreach ($request->getData() as $key => $item) {
            $this->result[$key] = 'done!';
        }
        return $this->buildResponse();
    }

    /**
     * Create a response object based on which response will be
     * build and sent to user by whoever receives it - echoed
     * as json by an API for example.
     *
     * @return ResponseInterface
     * @throws \Malt\Api\Exceptions\InvalidResponseStatusCodeException
     */
    protected function buildResponse(): ResponseInterface
    {
        return new Response(200, $this->result);
    }
}