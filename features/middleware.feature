Feature: HTTP Sessions Middleware
    In order to persist state information between page requests
    As a developer
    I want a request handler that starts a session, processes the request, and then closes the session

    Scenario: Session initialization
        Given the session middleware
        And the request
        And the request handler
        When the middleware is processed
        Then a new session is started
        Then the request is handled by the request handler
        Then the session is closed

    Scenario: Session
        Given constructor with no configuration parameters
        And the request
        And the request handler
        When the middleware is processed
        Then default session configuration parameters were used
        Then the request is handled by the request handler

    Scenario: Session id
        Given constructor with argument _custom_session_id_
        And the request
        And the request handler
        When the middleware is processed
        Then the currently set session id is changed  to _custom_session_id_
        Then the request is handled by the request handler

    Scenario Outline: Override session configuration directives
        Given constructor with argument <directive>
        And the request
        And the request handler
        When the middleware is processed
        Then the directive is changed to <value>
        Then the request is handled by the request handler

        Examples:
        | directive     | value                 |
        | name          | _custom_name_         |
        | cookie_domain | example.com           |
        | save_path     |  example1/example2/   |


    Scenario: Session has already started
        Given there is a session already started
        When the middleware is processed
        Then an exception is thrown

    Scenario: Sessions are disabled
        Given sessions are disabled
        When the middleware is processed
        Then an exception is thrown