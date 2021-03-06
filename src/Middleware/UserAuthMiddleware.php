<?php

namespace App\Middleware;

use App\Domain\User\Data\UserAuthData;
use App\Domain\User\Service\UserAuth;
use App\Responder\Responder;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware.
 */
final class UserAuthMiddleware implements MiddlewareInterface
{
    /**
     * @var Responder
     */
    private $responder;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var UserAuth
     */
    private $auth;

    /**
     * The constructor.
     *
     * @param Responder $responder The responder
     * @param SessionInterface $session The session
     * @param UserAuth $auth The user auth
     */
    public function __construct(
        Responder $responder,
        SessionInterface $session,
        UserAuth $auth
    ) {
        $this->responder = $responder;
        $this->session = $session;
        $this->auth = $auth;
    }

    /**
     * Invoke middleware.
     *
     * @param ServerRequestInterface $request The request
     * @param RequestHandlerInterface $handler The handler
     *
     * @return ResponseInterface The response
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $this->session->get('user');

        if ($user instanceof UserAuthData) {
            // User is logged in
            $this->auth->setUser($user);

            return $handler->handle($request);
        }

        // User is not logged in. Redirect to login page.
        return $this->responder->withRedirect($this->responder->createResponse(), 'login');
    }
}
