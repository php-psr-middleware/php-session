<?php
declare(strict_types = 1);

namespace PsrMiddlewares;


class SessionStatus
{
    public function isActive()
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    public function isDisabled()
    {
        return session_status() === PHP_SESSION_DISABLED;
    }
}