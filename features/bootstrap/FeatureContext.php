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
        $this->sessionMiddleware = new \PsrMiddlewares\PhpSession();
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
        $this->handler = new Handler();
    }

    /**
     * @When the middleware is processed
     */
    public function theMiddlewareIsProcessed()
    {
        $this->response = $this->sessionMiddleware->process($this->request, $this->handler);
    }

    /**
     * @Then a new session is started
     */
    public function aNewSessionIsStarted()
    {
        throw new PendingException();
    }

    /**
     * @Then the request is handled by the request handler
     */
    public function theRequestIsHandledByTheRequestHandler()
    {
        throw new PendingException();
    }

    /**
     * @Then the session is closed
     */
    public function theSessionIsClosed()
    {
        throw new PendingException();
    }

    /**
     * @Given constructor with no configuration parameters
     */
    public function constructorWithNoConfigurationParameters()
    {
        $this->sessionMiddleware = new \PsrMiddlewares\PhpSession();
    }

    /**
     * @Then default session configuration parameters were used
     */
    public function defaultSessionConfigurationParametersWereUsed()
    {
        throw new PendingException();
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
}


class Handler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response('php://memory', 200);
    }
}