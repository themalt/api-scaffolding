<?php

declare(strict_types=1);

use CodeLibrary\Php\Api\Common\AbstractAction;
use CodeLibrary\Php\Api\Common\Interfaces\ActionInterface;
use CodeLibrary\Php\Api\Common\Interfaces\ResponseInterface;
use CodeLibrary\Php\Api\Common\Request;
use CodeLibrary\Php\Api\Sandbox\Actions\GetShitDoneAction;
use PHPUnit\Framework\TestCase;

final class GetShitDoneActionTest extends TestCase
{
    public function setUp()
    {
        // Mock some global request data
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_HOST'] = 'api.com';
        $_SERVER['REQUEST_URI'] = '/testIt.php?wtf=false#bang';
        $_REQUEST['username'] = 'misio';
        $_REQUEST['password'] = 'miodek';
        $_REQUEST['action'] = 'RunATest';
        $_REQUEST['data'] = urlencode(serialize([
            'Prepare mock data' => 'pending',
            'Write unit tests' => 'in progress',
            'Check coverage' => 'pending'
        ]));
    }

    public function testCreateAction(): void
    {
        $action = new GetShitDoneAction();
        $this->assertInstanceOf(ActionInterface::class, $action);
        $this->assertInstanceOf(AbstractAction::class, $action);
    }

    public function testExecute(): void
    {
        $request = new Request();
        $action = new GetShitDoneAction();
        $response = $action->execute($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $expectedData = [
            'Prepare mock data' => 'done!',
            'Write unit tests' => 'done!',
            'Check coverage' => 'done!'
        ];
        $this->assertEquals($expectedData, $response->getData());
    }
}