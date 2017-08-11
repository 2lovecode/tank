<?php
require(__DIR__ . '/../vendor/tank/tank-core/Tank.php');


$config = [
    'mds' => 'Hello God!',
];
require(__DIR__ . '/../vendor/autoload.php');

(new tank\web\Application($config))->run();