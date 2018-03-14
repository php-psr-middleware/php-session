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

    public function __construct(
        SessionStatus $status, string $id = '', array $options = []
    )
    {
        $this->status = $status;
        $this->id = $id;
        $this->options =  $options;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if(!empty($this->id)) {
            session_id($this->id);
        }

        if (empty($this->options)) {
            session_start();
        } else {
            session_start($this->options);
        }

        $response = $handler->handle($request);

        if ($this->status->isActive()) {
            session_write_close();
        }
        return $response;
    }
}