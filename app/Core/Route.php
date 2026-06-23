<?php

namespace App\Core;

class Route
{
    public static function loadRoutes(string $routes_dir): void
    {
        foreach (glob($routes_dir . "/*.php") as $route) {
            if (file_exists($route)) {
                require_once $route;
            }
        }
    }
}