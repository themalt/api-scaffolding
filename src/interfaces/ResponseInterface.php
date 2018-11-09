<?php

namespace CodeLibrary\Php\Api\Common\Interfaces;

interface ResponseInterface
{
    public function getData(): array;
    public function getStatusCode(): int;
}