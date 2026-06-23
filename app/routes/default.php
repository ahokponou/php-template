<?php

use App\Controller\HomeController;
use Oladesoftware\Httpcrafter\Router\RouterFacade;

RouterFacade::get("/", [HomeController::class, "index"], "home");