<?php

namespace App\Controller;

use Oladesoftware\Httpcrafter\Http\Response;

class Controller
{
    protected function render(
        string $result,
        array $data = [],
        int $code = 200,
        array $headers = []
    ): Response
    {
        if ($result === "error") {
            return new Response(
                [
                    "result" => $result,
                    "error" => $data,
                ],
                Response::JSON,
                $code,
                $headers
            );
        }

        return new Response(
            [
                "result" => $result,
                "data" => $data,
            ],
            Response::JSON,
            $code,
            $headers
        );
    }
}