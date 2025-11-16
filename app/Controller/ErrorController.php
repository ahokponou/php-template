<?php

namespace App\Controller;

class ErrorController extends Controller
{
    public function notFound(): string
    {
        return $this->render(
            "error",
            [
                "code" => "404",
                "message" => "Resource not found"
            ],
            404
        )->send();
    }

    public function internalError(): string
    {
        return $this->render(
            "error",
            [
                "code" => "500",
                "message" => "Internal error"
            ],
            500
        )->send();
    }
}