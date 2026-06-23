<?php

use App\Controller\ErrorController;
use App\Core\Logger;
use App\Core\RequestFactory;
use App\Core\Route;
use Oladesoftware\Httpcrafter\Router\RouterFacade;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

require_once dirname(__DIR__) . '/vendor/autoload.php';

Logger::init("phpjstemplate", dirname(__DIR__) . "/var/log");

const MODE = "development";
define("ROOT_DIR", dirname(__DIR__));
const PUBLIC_ASSETS_PATH = "/assets";

try {
    Route::loadRoutes(dirname(__DIR__) . "/app/routes");

    $matchedRoute = RouterFacade::getRouter()->match(
        RequestFactory::getInstance()->getPath(),
        RequestFactory::getInstance()->getMethod()
    );

    if (!$matchedRoute) {
        if (MODE !== "development") {
            Logger::error(
                RequestFactory::getInstance()->getMethod() . " " . RequestFactory::getInstance()->getPath() . " does not match.",
                RequestFactory::getInstance()->getServer()
            );
        }

        echo new ErrorController()->notFound();
        exit();
    }

    echo RouterFacade::getRouter()->run($matchedRoute);
    exit();
} catch (Throwable $t) {
    if (MODE === "development") {
        $whoops = new Run;
        $whoops->allowQuit(false);
        $whoops->writeToOutput(false);
        $whoops->pushHandler(new PrettyPageHandler);
        echo $whoops->handleException($t);
        exit();
    }

    Logger::error(
        $t->getMessage(),
        [
            "code" => $t->getCode(),
            "line" => $t->getLine(),
            "file" => $t->getFile(),
            "trace" => $t->getTrace()
        ]
    );

    echo new ErrorController()->internalError();
    exit();
}