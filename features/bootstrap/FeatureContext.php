<?php
declare(strict_types = 1);

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;

class FeatureContext implements Context
{
    private $handler;
    private $request;
    private $middleware;


    /**
     * @Given the session middleware
     */
    public function theSessionMiddleware()
    {
        $this->middleware = new \PsrMiddlewares\PhpSession();
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
        $this->handler = new \PsrMiddlewares\NullRequestHandler(200);
    }

    /**
     * @When the middleware is processed
     */
    public function theMiddlewareIsProcessed()
    {
        $this->middleware->process($this->request, $this->handler);
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
        $this->middleware = new \PsrMiddlewares\PhpSession();
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
