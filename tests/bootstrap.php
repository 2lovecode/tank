<?php
/**
 * User: liuhao
 * Date: 18-1-19
 * Time: 下午5:10
 */

error_reporting(-1);

define('TANK_DEBUG', true);

$_SERVER['SCRIPT_NAME'] = '/' . __DIR__;
$_SERVER['SCRIPT_FILENAME'] = __FILE__;

$composerAutoloadFile = __DIR__ . '/../vendor/autoload.php';


if (is_file($composerAutoloadFile)) {
    require_once($composerAutoloadFile);
}
require_once __DIR__ . '/../framework/Tank.php';


Tank::registerAlias('@@tankunit', __DIR__);
if (getenv('TEST_RUNTIME_PATH')) {
    Tank::registerAlias('@@tankunit/runtime', getenv('TEST_RUNTIME_PATH'));
    Tank::registerAlias('@@runtime', getenv('TEST_RUNTIME_PATH'));
}
require_once __DIR__ . '/TankTestCase.php';