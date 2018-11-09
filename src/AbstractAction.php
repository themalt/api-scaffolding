<?php

namespace CodeLibrary\Php\Api\Common;

use CodeLibrary\Php\Api\Common\Interfaces\ActionInterface;
use CodeLibrary\Php\Api\Common\Interfaces\ResponseInterface;

abstract class AbstractAction implements ActionInterface
{
    abstract protected function buildResponse(): ResponseInterface;
}