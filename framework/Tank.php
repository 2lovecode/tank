<?php

require(__DIR__ . '/BaseTank.php');

class Tank extends \tank\BaseTank
{
}

spl_autoload_register(['Tank', 'autoload'], true, true);
Tank::$container = new \tank\di\Container();