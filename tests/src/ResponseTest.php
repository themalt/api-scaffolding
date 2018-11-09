<?php

declare(strict_types=1);

use Malt\Api\Interfaces\ResponseInterface;
use Malt\Api\Response;
use PHPUnit\Framework\TestCase;

final class ResponseTest extends TestCase
{
    private $sampleData = [
        'tick' => true,
        'tack' => false,
        'toe' => 666
    ];

    /**
     * @throws \Malt\Api\Exceptions\InvalidResponseStatusCodeException
     */
    public function testCreateProperObject(): void
    {
       $response = new Response(200, $this->sampleData);
       $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    /**
     * @throws \Malt\Api\Exceptions\InvalidResponseStatusCodeException
     */
    public function testGetStatusCode(): void
    {
        $response = new Response(200, $this->sampleData);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEquals(500, $response->getStatusCode());
    }

    /**
     * @throws \Malt\Api\Exceptions\InvalidResponseStatusCodeException
     */
    public function testGetData(): void
    {
        $response = new Response(200, $this->sampleData);
        $this->assertEquals($this->sampleData, $response->getData());
        $this->assertNotEquals(array_keys($this->sampleData), $response->getData());
    }

    /**
     * @throws \Malt\Api\Exceptions\InvalidResponseStatusCodeException
     */
    public function testGetJsonData(): void
    {
        $response = new Response(200, $this->sampleData);
        $this->assertEquals(json_encode($this->sampleData), $response->getJsonData());
        $this->assertNotEquals(json_encode(array_keys($this->sampleData)), $response->getJsonData());
    }


    /**
     * @throws \Malt\Api\Exceptions\InvalidResponseStatusCodeException
     */
    public function testGetHttpHeader()
    {
        $response = new Response(200, $this->sampleData);
        $this->assertEquals(Response::HTTP_RESPONSE_HEADERS[200], $response->getHttpHeader());
        $this->assertNotEquals(Response::HTTP_RESPONSE_HEADERS[500], $response->getHttpHeader());
        $response = new Response(401, $this->sampleData);
        $this->assertEquals(Response::HTTP_RESPONSE_HEADERS[401], $response->getHttpHeader());
        $this->assertNotEquals(Response::HTTP_RESPONSE_HEADERS[200], $response->getHttpHeader());
    }

    /**
     * @expectedException Malt\Api\Exceptions\InvalidResponseStatusCodeException
     */
    public function testInvalidReponseStatusCode(): void
    {
        $response = new Response(333, $this->sampleData);
    }
}