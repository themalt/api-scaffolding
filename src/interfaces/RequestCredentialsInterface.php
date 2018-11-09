<?php

namespace CodeLibrary\Php\Api\Common\Interfaces;

interface RequestCredentialsInterface
{
    /**
     * Verifies if two sets of credentials match each other.
     *
     * @param RequestCredentialsInterface $credentials
     * @return bool
     */
    public function match(RequestCredentialsInterface $credentials) : bool;

    /**
     * Returns plain password.
     *
     * @return string
     */
    public function getPassword();

    /**
     * Returns plain username.
     *
     * @return string
     */
    public function getUsername();
}