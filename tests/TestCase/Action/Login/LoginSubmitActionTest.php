<?php

namespace App\Test\TestCase\Action\Login;

use App\Domain\User\Data\UserAuthData;
use App\Test\Fixture\UserFixture;
use App\Test\Traits\AppTestTrait;
use App\Test\Traits\DatabaseTestTrait;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class LoginSubmitActionTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;

    /**
     * Test.
     *
     * @return void
     */
    public function testLoginAdminAction(): void
    {
        $this->insertFixtures(
            [
                UserFixture::class,
            ]
        );

        $request = $this->createJsonRequest('POST', '/login', ['username' => 'admin', 'password' => 'admin']);
        $response = $this->app->handle($request);

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame('/users', $response->getHeaderLine('Location'));
        $this->assertEquals('', (string)$response->getBody());

        // User session
        $session = $this->getSession();

        /** @var UserAuthData $user */
        $user = $session->get('user');
        $this->assertInstanceOf(UserAuthData::class, $user);
        $this->assertSame(1, $user->id);
        $this->assertSame('en_US', $user->locale);
        $this->assertSame('admin@example.com', $user->email);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testLoginUserAction(): void
    {
        $this->insertFixtures(
            [
                UserFixture::class,
            ]
        );

        $request = $this->createJsonRequest('POST', '/login', ['username' => 'user', 'password' => 'user']);
        $response = $this->app->handle($request);

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame('/users', $response->getHeaderLine('Location'));
        $this->assertEquals('', (string)$response->getBody());

        // User session
        $session = $this->getSession();

        /** @var UserAuthData $user */
        $user = $session->get('user');
        $this->assertInstanceOf(UserAuthData::class, $user);
        $this->assertSame(2, $user->id);
        $this->assertSame('de_DE', $user->locale);
        $this->assertSame('user@example.com', $user->email);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testLoginFailed(): void
    {
        $this->insertFixtures(
            [
                UserFixture::class,
            ]
        );

        $request = $this->createJsonRequest('POST', '/login', ['username' => 'foo', 'password' => 'foo']);
        $response = $this->app->handle($request);

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame('/login', $response->getHeaderLine('Location'));
        $this->assertEquals('', (string)$response->getBody());
    }
}
