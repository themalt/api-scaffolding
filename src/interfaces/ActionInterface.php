<?php

namespace CodeLibrary\Php\Api\Common\Interfaces;

interface ActionInterface
{
    function execute(RequestInterface $request): ResponseInterface;
}