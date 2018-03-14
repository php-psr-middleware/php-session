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

    Scenario: Session with default configuration
        Given constructor with no configuration parameters
        And the request
        And the request handler
        When the middleware is processed
        Then default session configuration parameters were used
        Then the request is handled by the request handler

    Scenario: Session id
        Given constructor with argument "custom-session-id"
        And the request
        And the request handler
        When the middleware is processed
        Then the currently set session id is changed  to "custom-session-id"
        Then the request is handled by the request handler

    Scenario Outline: Override session configuration directives
        Given constructor with options array that includes "<directive>" and "<value>"
        And the request
        And the request handler
        When the middleware is processed
        Then the <directive> is changed to "<value>"
        Then the request is handled by the request handler

        Examples:
        | directive       | value                 |
        | name            | _custom_name_         |
        | cookie_lifetime | 600                   |
        | cookie_domain   | example.com           |