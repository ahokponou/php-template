<?php

namespace App\Controller;

use Oladesoftware\Httpcrafter\Http\Response;

class Controller
{
    protected string $view_dir;
    protected string $template_dir;

    public function __construct()
    {
        $this->view_dir = dirname(__DIR__) . "/view";
        $this->template_dir = $this->view_dir . "/template";
    }

    protected function render(
        string $view_name,
        array $data = [],
        int $code = 200,
        string $template_name = "empty",
        array $headers = []
    ): Response
    {
        return new Response(
            $this->getPage($view_name, $template_name, $data),
            Response::HTML,
            $code,
            $headers
        );
    }

    private function getPage(
        string $view_name,
        string $template_name,
        array $data = []
    ): string
    {
        $view_file = $this->view_dir . "/" . $view_name . ".php";
        $template_file = $this->template_dir . "/" . $template_name . ".php";

        extract($data);
        if (file_exists($view_file)) {
            ob_start();
            require $view_file;
            $body = ob_get_clean();
        } else {
            $body = "";
        }

        if (!file_exists($template_file)) {
            $template_file = $this->view_dir . "/empty.php";

            if (!file_exists($template_file)) {
                $page = "No template file found";
            }
        }

        extract($data);
        ob_start();
        require $template_file;
        return ob_get_clean();
    }
}