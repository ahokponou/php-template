<?php

namespace App\Controller;

class HomeController extends Controller
{
    public function index():string
    {
        return $this->render(
            "sucess",
            [
                "PHP Template"
            ]
        )->send();
    }
}