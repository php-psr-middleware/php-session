<?php
declare(strict_types = 1);

namespace PsrMiddlewares;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;


class PhpSession implements MiddlewareInterface
{
    private $status;

    public function __construct(SessionStatus $status)
    {
        $this->status = $status;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        session_start();

        $response = $handler->handle($request);

        if ($this->status->isActive()) {
            session_write_close();
        }
        return $response;
    }
}