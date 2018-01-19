<?php


require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../framework/Tank.php');


Tank::registerAlias('@@aa', 'p1/p2');
Tank::registerAlias('@@aa/bb', 'p1/p2');
Tank::registerAlias('@@bb', 'p3/p2');
Tank::registerAlias('@@bb/cc/dd', 'p0/p9');
//Tank::registerAlias('@@aa', 'p1/p2');
echo '<pre>';
var_dump(Tank::$aliasMap);