<?php

declare(strict_types=1);

use Malt\Api\Interfaces\RequestInterface;
use Malt\Api\Request;
use Malt\Api\RequestCredentials;
use PHPUnit\Framework\TestCase;

final class RequestTest extends TestCase
{
    private function mockRequestGlobals($setId)
    {
        switch ($setId) {
            default:
            case 1:
                // Mock some global request data
                $_SERVER['REQUEST_METHOD'] = 'GET';
                $_SERVER['HTTPS'] = 'on';
                $_SERVER['HTTP_HOST'] = 'api.com';
                $_SERVER['REQUEST_URI'] = '/testIt.php?wtf=false#bang';
                $_REQUEST['username'] = 'misio';
                $_REQUEST['password'] = 'miodek';
                $_REQUEST['action'] = 'RunATest';
                $_REQUEST['data'] = urlencode(serialize([
                    'width' => 10,
                    'height' => 20,
                    'letters' => ['a','b','c']
                ]));
                break;
            case 2:
                // double check on different data set
                $_SERVER['REQUEST_METHOD'] = 'POST';
                $_SERVER['HTTPS'] = null;
                $_SERVER['HTTP_HOST'] = 'secret.com';
                $_SERVER['REQUEST_URI'] = '?show=42';
                $_REQUEST['username'] = 'johndoe';
                $_REQUEST['password'] = 'unknown';
                $_REQUEST['action'] = 'SaveProduct';
                $_REQUEST['data'] = urlencode(serialize([
                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    'price' => 666.6
                ]));
                break;
        }
    }

    public function testCreateObject(): void
    {
        $this->mockRequestGlobals(1);
        $request = new Request();
        $this->assertInstanceOf(RequestInterface::class, $request);
    }

    public function testGetData(): void
    {
        $this->mockRequestGlobals(1);
        $request = new Request();
        $this->assertEquals(10, $request->getData()['width']);
        $this->assertEquals(20, $request->getData()['height']);
        $this->assertEquals(['a','b','c'], $request->getData()['letters']);
        $this->assertNotEquals(1, $request->getData()['width']);
        $this->assertNotEquals(2, $request->getData()['height']);
        $this->assertNotEquals(['a','b'], $request->getData()['letters']);

        $this->mockRequestGlobals(2);
        $request = new Request();
        $this->assertInstanceOf(RequestInterface::class, $request);
        $this->assertEquals('Lorem ipsum dolor sit amet, consectetur adipiscing elit.', $request->getData()['description']);
        $this->assertEquals(666.6, $request->getData()['price']);
        $this->assertNotEquals('', $request->getData()['description']);
        $this->assertNotEquals(8, $request->getData()['price']);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidDataAsArray(): void
    {
        $this->mockRequestGlobals(1);
        $_REQUEST['data'] = ['bazinga'];
        new Request();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidDataAsMalformedString(): void
    {
        $this->mockRequestGlobals(1);
        $_REQUEST['data'] = 'bazinga';
        new Request();
    }

    public function testGetCredentials(): void
    {
        $this->mockRequestGlobals(1);
        $request = new Request();
        $this->assertTrue($request->getCredentials()->match(new RequestCredentials('misio', 'miodek')));
        $this->assertFalse($request->getCredentials()->match(new RequestCredentials('blah', 'puff')));
        $this->mockRequestGlobals(2);
        $request = new Request();
        $this->assertTrue($request->getCredentials()->match(new RequestCredentials('johndoe', 'unknown')));
        $this->assertFalse($request->getCredentials()->match(new RequestCredentials('hey', 'man!')));
    }

    public function testGetMethod(): void
    {
        $this->mockRequestGlobals(1);
        $request = new Request();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertNotEquals('POST', $request->getMethod());
        $this->mockRequestGlobals(2);
        $request = new Request();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertNotEquals('GET', $request->getMethod());
    }

    public function getAction(): void
    {
        $this->mockRequestGlobals(1);
        $request = new Request();
        $this->assertEquals('RunATest', $request->getAction());
        $this->assertNotEquals('DontRunATest', $request->getAction());
        $this->mockRequestGlobals(2);
        $request = new Request();
        $this->assertEquals('SaveProduct', $request->getAction());
        $this->assertNotEquals('DontSaveProduct', $request->getAction());
    }

    public function testGetUri(): void
    {
        $this->mockRequestGlobals(1);
        $request = new Request();
        $this->assertEquals('https://api.com/testIt.php?wtf=false#bang', $request->getUri()->getFullUrl());
        $this->assertNotEquals('http://api.com/testIt.php?wtf=false', $request->getUri()->getFullUrl());
        $this->mockRequestGlobals(2);
        $request = new Request();
        $this->assertEquals('http://secret.com?show=42', $request->getUri()->getFullUrl());
        $this->assertNotEquals('https://api.com/testIt.php?wtf=false#bang', $request->getUri()->getFullUrl());
    }
}