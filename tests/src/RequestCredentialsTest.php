<?php

declare(strict_types=1);

use Malt\Api\Interfaces\RequestCredentialsInterface;
use Malt\Api\RequestCredentials;
use PHPUnit\Framework\TestCase;

final class RequestCredentialsTest extends TestCase
{

    public function testCreateProperObject(): void
    {
        $credentials = new RequestCredentials('misio', 'miodek');
        $this->assertInstanceOf(RequestCredentialsInterface::class, $credentials);
        $this->assertEquals('misio', $credentials->getUsername());
        $this->assertEquals('miodek', $credentials->getPassword());
        $this->assertNotEquals('someone_else', $credentials->getUsername());
        $this->assertNotEquals('wrong_password', $credentials->getPassword());
    }

    public function testMatchCredentials(): void
    {
        $credentials = new RequestCredentials('misio', 'miodek');
        $theSameCredentials = new RequestCredentials('misio', 'miodek');
        $differentCredentials = new RequestCredentials('someone_else', 'wrong_password');

        $this->assertTrue($credentials->match($theSameCredentials));
        $this->assertFalse($credentials->match($differentCredentials));
    }
}