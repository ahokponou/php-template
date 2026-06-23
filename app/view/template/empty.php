<?php
    $manifest_file = ROOT_DIR . "/public/assets/.vite/manifest.json";

    if (file_exists($manifest_file)) {
        $manifest = json_decode(file_get_contents($manifest_file), true);
    } else {
        $manifest = [];
    }
?>
<!doctype html>
<html lang="<?= $lang ?? "" ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="/images/template.svg" type="image/svg+xml">
    <title><?= $title ?? "" ?></title>

    <?php if (MODE === "development"): ?>
        <script type="module" src="http://<?= explode(":", $_SERVER["HTTP_HOST"])[0] ?>:5173/@vite/client"></script>
        <script type="module" src="http://<?= explode(":", $_SERVER["HTTP_HOST"])[0] ?>:5173/app/assets/js/main.js"></script>
    <?php else: ?>
        <?php if (!empty($manifest)): ?>
            <?php foreach ($manifest as $assets): ?>
                <script type="module" src="<?= PUBLIC_ASSETS_PATH . "/" . $assets["file"] ?>"></script>
                <?php foreach ($assets["css"] as $css): ?>
                    <link rel="stylesheet" href="<?= PUBLIC_ASSETS_PATH . "/" . $css ?>">
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
</head>
<body>
    <?= $body ?? "" ?>
</body>
</html>