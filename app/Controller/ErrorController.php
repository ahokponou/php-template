<?php

namespace App\Controller;

class ErrorController extends Controller
{
    public function notFound(): string
    {
        return $this->render("error/notFound", [], 404)->send();
    }

    public function internalError(): string
    {
        return $this->render("error/internalError", [], 500)->send();
    }
}