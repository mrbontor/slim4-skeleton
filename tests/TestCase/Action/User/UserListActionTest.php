<?php

namespace App\Test\TestCase\Action\User;

use App\Test\Traits\AppTestTrait;
use App\Test\Traits\DatabaseTestTrait;
use App\Test\Traits\LoginTestTrait;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class UserListActionTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;
    use LoginTestTrait;

    /**
     * Test.
     *
     * @return void
     */
    public function testUsersWithoutLoginAction(): void
    {
        $request = $this->createRequest('GET', '/users');
        $response = $this->app->handle($request);

        $this->assertSame(302, $response->getStatusCode());
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testUsersWithLoginAction(): void
    {
        $this->loginUser();
        $request = $this->createRequest('GET', '/users');
        $response = $this->app->handle($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('User list', (string)$response->getBody());
    }
}
