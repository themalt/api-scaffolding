<?php

namespace CodeLibrary\Php\Api\Common;

use CodeLibrary\Php\Api\Common\Interfaces\RequestCredentialsInterface;

final class RequestCredentials implements RequestCredentialsInterface
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * RequestCredentials constructor.
     *
     * @param string $username
     * @param string $password
     */
    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @inheritdoc
     */
    public function match(RequestCredentialsInterface $requestCredentials): bool
    {
        return $this->getUsername() === $requestCredentials->getUsername()
            && $this->getPassword() === $requestCredentials->getPassword();
    }

    /**
     * @inheritdoc
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @inheritdoc
     */
    public function getUsername(): string
    {
        return $this->username;
    }


}