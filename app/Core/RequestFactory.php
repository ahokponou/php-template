<?php

namespace App\Core;

use Oladesoftware\Httpcrafter\Http\Request;

class RequestFactory
{
    private static ?Request $instance = null;

    public static function getInstance(): Request
    {
        if (null === self::$instance) {
            self::setInstance(new Request($_SERVER, $_GET, $_POST));
        }

        return self::$instance;
    }

    private static function setInstance(Request $instance): void
    {
        self::$instance = $instance;
    }
}