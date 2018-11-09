<?php

declare(strict_types=1);

use CodeLibrary\Php\Api\Common\Interfaces\RequestInterface;
use CodeLibrary\Php\Api\Common\Request;
use CodeLibrary\Php\Api\Common\RequestCredentials;
use CodeLibrary\Php\Api\Common\Response;
use CodeLibrary\Php\Api\Sandbox\Actions\GetShitDoneAction;
use CodeLibrary\Php\Api\Sandbox\SandboxApi;
use CodeLibrary\Tests\Php\TestingPrivateMethodsTrait;
use PHPUnit\Framework\TestCase;

final class SandboxApiTest extends TestCase
{
    use TestingPrivateMethodsTrait;

    /**
     * @throws ReflectionException
     */
    public function testBuildAction(): void
    {
        $requestMock = $this->createMock(Request::class);
        $requestMock->method('getAction')
            ->willReturn('GetShitDone');
        $api = new SandboxApi(new RequestCredentials('sandbox', 'rakeAndBucket'));
        $action = $this->invokePrivateMethod($api, 'buildAction', [
            $requestMock
        ]);

        $this->assertInstanceOf(GetShitDoneAction::class, $action);
    }

    /**
     * @throws ReflectionException
     * @expectedException CodeLibrary\Php\Api\Exception\UnknownActionException
     */
    public function testBuildNonExistingAction(): void
    {
        $requestMock = $this->createMock(Request::class);
        $requestMock->method('getAction')
            ->willReturn('IDoNotExist');
        $api = new SandboxApi(new RequestCredentials('sandbox', 'rakeAndBucket'));
        $this->invokePrivateMethod($api, 'buildAction', [
            $requestMock
        ]);
    }

    /**
     * @throws ReflectionException
     */
    public function testValidateRequest(): void
    {
        $sampleCredentials = new RequestCredentials('ping', 'pong');
        $matchingCredentials = new RequestCredentials('ping', 'pong');
        $requestMock = $this->createMock(Request::class);
        $requestMock->method('getCredentials')
            ->willReturn($sampleCredentials);
        $api = new SandboxApi($sampleCredentials);
        $validationResult = $this->invokePrivateMethod($api, 'validateRequest', [
            $requestMock,
            $matchingCredentials
        ]);
        $this->assertTrue($validationResult);

        $sampleCredentials = new RequestCredentials('ping', 'pong');
        $matchingCredentials = new RequestCredentials('ding', 'dong');
        $requestMock = $this->createMock(Request::class);
        $requestMock->method('getCredentials')
            ->willReturn($sampleCredentials);
        $api = new SandboxApi($sampleCredentials);
        $validationResult = $this->invokePrivateMethod($api, 'validateRequest', [
            $requestMock,
            $matchingCredentials
        ]);
        $this->assertFalse($validationResult);
    }

    /**
     * @throws ReflectionException
     */
    public function testBuildRequest(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['HTTP_HOST'] = 'api.com';
        $_SERVER['REQUEST_URI'] = '/testIt.php?wtf=false#bang';
        $_REQUEST['username'] = 'misio';
        $_REQUEST['password'] = 'miodek';
        $_REQUEST['action'] = 'RunATest';
        $_REQUEST['data'] = [];

        $api = new SandboxApi(new RequestCredentials('ping', 'pong'));
        $request = $this->invokePrivateMethod($api, 'buildRequest', []);
        $this->assertInstanceOf(RequestInterface::class, $request);
    }

    /**
     * @runInSeparateProcess
     */
    public function testRun(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['HTTP_HOST'] = 'api.com';
        $_SERVER['REQUEST_URI'] = '/testIt.php?wtf=false#bang';
        $_REQUEST['username'] = 'ping';
        $_REQUEST['password'] = 'pong';
        $_REQUEST['action'] = 'GetShitDone';
        $_REQUEST['data'] = urlencode(serialize($_REQUEST['data'] = [
            'tick' => 'pending',
            'tack' => 'pending',
            'toe' => 'pending'
        ]));

        $credentials = new RequestCredentials($_REQUEST['username'], $_REQUEST['password']);
        $api = new SandboxApi($credentials);

        $api->run();
        $this->expectOutputString('{"tick":"done!","tack":"done!","toe":"done!"}');
    }

    /**
     * @runInSeparateProcess
     * @throws \CodeLibrary\Php\Api\Exception\InvalidResponseStatusCodeException
     */
    public function testRunInvalidCredentials(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['HTTP_HOST'] = 'api.com';
        $_SERVER['REQUEST_URI'] = '/testIt.php?wtf=false#bang';
        $_REQUEST['username'] = 'ping';
        $_REQUEST['password'] = 'pong';
        $_REQUEST['action'] = 'GetShitDone';
        $_REQUEST['data'] = urlencode(serialize([
            'tick' => 'pending',
            'tack' => 'pending',
            'toe' => 'pending'
        ]));

        $credentials = new RequestCredentials('bazinga', 'bazinga');
        $api = new SandboxApi($credentials);

        $api->run();
        $expectedResponse = new Response(401, [
            'Invalid credentials.'
        ]);
        $this->expectOutputString($expectedResponse->getJsonData());
    }

}