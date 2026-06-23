<?php

use Oladesoftware\Httpcrafter\Router\RouterFacade;

$title = "Page not found";
?>

<main class="error">
    <div class="error-body">
        <h1>404 | <?= $title ?></h1>
        <a href="<?= RouterFacade::generatePath("home") ?>">Back to home</a>
    </div>
</main>