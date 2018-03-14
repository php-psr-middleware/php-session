<?php
declare(strict_types = 1);

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;

class FeatureContext implements Context
{
    private $request;
    private $handler;
    private $response;
    private $sessionMiddleware;

    /**
     * @Given the session middleware
     */
    public function theSessionMiddleware()
    {
        $this->sessionMiddleware = new \PsrMiddlewares\PhpSession(new \PsrMiddlewares\SessionStatus);
    }

    /**
     * @Given the request
     */
    public function theRequest()
    {
        $this->request = new \PsrMiddlewares\ServerRequest();
    }

    /**
     * @Given the request handler
     */
    public function theRequestHandler()
    {
        $this->handler = new Handler([
            function() { return session_status(); },
            function() { return true; }
        ]);
    }

    /**
     * @When the middleware is processed
     */
    public function theMiddlewareIsProcessed()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            throw new RuntimeException('PHP sessions are already active');
        }
        $this->process();
    }

    /**
     * @Then a new session is started
     */
    public function aNewSessionIsStarted()
    {
        Assert::assertSame($this->handler->results[0], PHP_SESSION_ACTIVE);
    }

    /**
     * @Then the request is handled by the request handler
     */
    public function theRequestIsHandledByTheRequestHandler()
    {
        Assert::assertSame($this->handler->results[1], true);
    }

    /**
     * @Then the session is closed
     */
    public function theSessionIsClosed()
    {
        Assert::assertSame(session_status(), PHP_SESSION_NONE);
    }

    /**
     * @Given constructor with no configuration parameters
     */
    public function constructorWithNoConfigurationParameters()
    {
        $this->sessionMiddleware = new \PsrMiddlewares\PhpSession(new \PsrMiddlewares\SessionStatus);
    }

    /**
     * @Then default session configuration parameters were used
     */
    public function defaultSessionConfigurationParametersWereUsed()
    {
        $sessionName = session_name();
        $sessionId = session_id();

        $this->handler->addCallable(
            'session_name', function() { return session_name(); }
        );
        $this->handler->addCallable(
            'session_id', function() { return session_id(); }
        );
        $this->process();

        Assert::assertSame($this->handler->results['session_name'], $sessionName);
        Assert::assertSame($this->handler->results['session_id'], $sessionId);
    }

    /**
     * @Given constructor with argument :arg1
     */
    public function constructorWithArgument(string $arg1)
    {
        $this->sessionMiddleware = new \PsrMiddlewares\PhpSession(
            new \PsrMiddlewares\SessionStatus,
            $arg1
        );
    }

    /**
     * @Then the currently set session id is changed  to :arg1
     */
    public function theCurrentlySetSessionIdIsChangedTo(string $arg1)
    {
        $this->handler->addCallable(
            'session_id', function() { return session_id(); }
        );

        $this->process();

        Assert::assertSame($this->handler->results['session_id'], $arg1);
    }

    /**
     * @Given constructor with options array that includes :arg1 and :arg2
     */
    public function constructorWithOptionsArrayThatIncludesAnd(string $arg1, $arg2)
    {
        $this->sessionMiddleware = new \PsrMiddlewares\PhpSession(
            new \PsrMiddlewares\SessionStatus,
            '',
            [$arg1 => $arg2]
        );
    }

    /**
     * @Then the name is changed to :arg1
     */
    public function theNameIsChangedTo($arg1)
    {
        $this->handler->addCallable(
            'directive_change', function() { return session_name(); }
        );

        $this->process();

        Assert::assertSame($this->handler->results['directive_change'], $arg1);
    }

    /**
     * @Then the cookie_domain is changed to :arg1
     */
    public function theCookieDomainIsChangedTo($arg1)
    {
        $this->handler->addCallable(
            'directive_change', function () {
            return session_get_cookie_params()['domain'];
        }
        );

        $this->process();

        Assert::assertSame($this->handler->results['directive_change'], $arg1);
    }

    /**
     * @Then the cookie_lifetime is changed to :arg1
     */
    public function theCookieLifetimeIsChangedTo(int $arg1)
    {
        $this->handler->addCallable(
            'directive_change', function () {
            return session_get_cookie_params()['lifetime'];
        }
        );

        $this->process();

        Assert::assertSame($this->handler->results['directive_change'], $arg1);
    }

    private function process()
    {
        $this->response = $this->sessionMiddleware->process($this->request, $this->handler);
    }
}


class Handler implements RequestHandlerInterface
{
    public $sessionWasActive;
    public $requestWasHandled = false;
    public $sessionName;
    public $callables = [];
    public $results = [];

    public function __construct(array $callables)
    {
        $this->callables = $callables;
    }

    public function addCallable(string $name, callable $callable)
    {
        $this->callables[$name] = $callable;
        return $this;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        foreach($this->callables as $key => $callable)
            $this->results[$key] = $callable();

        return new Response('php://memory', 200);
    }
}
