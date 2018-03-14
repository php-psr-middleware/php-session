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
        $this->process();

    }

    /**
     * @Given constructor with argument _custom_session_id_
     */
    public function constructorWithArgumentCustomSessionId()
    {
        throw new PendingException();
    }

    /**
     * @Then the currently set session id is changed  to _custom_session_id_
     */
    public function theCurrentlySetSessionIdIsChangedToCustomSessionId()
    {
        throw new PendingException();
    }

    /**
     * @Given constructor with argument name
     */
    public function constructorWithArgumentName()
    {
        throw new PendingException();
    }

    /**
     * @Then the directive is changed to _custom_name_
     */
    public function theDirectiveIsChangedToCustomName()
    {
        throw new PendingException();
    }

    /**
     * @Given constructor with argument cookie_domain
     */
    public function constructorWithArgumentCookieDomain()
    {
        throw new PendingException();
    }

    /**
     * @Then the directive is changed to example.com
     */
    public function theDirectiveIsChangedToExampleCom()
    {
        throw new PendingException();
    }

    /**
     * @Given constructor with argument save_path
     */
    public function constructorWithArgumentSavePath()
    {
        throw new PendingException();
    }

    /**
     * @Then the directive is changed to example1\/example2\/
     */
    public function theDirectiveIsChangedToExampleExample()
    {
        throw new PendingException();
    }

    /**
     * @Given there is a session already started
     */
    public function thereIsASessionAlreadyStarted()
    {
        throw new PendingException();
    }

    /**
     * @Then an exception is thrown
     */
    public function anExceptionIsThrown()
    {
        throw new PendingException();
    }

    /**
     * @Given sessions are disabled
     */
    public function sessionsAreDisabled()
    {
        throw new PendingException();
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

    public function addCallable(callable $callable)
    {
        $this->callables[] = $callable;
        return $this;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
//        $this->sessionWasActive = session_status();
//        $this->requestWasHandled = true;
        foreach($this->callables as $callable)
            $this->results[] = $callable();

        return new Response('php://memory', 200);
    }
}