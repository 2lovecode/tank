<?php
/**
 * Created by PhpStorm.
 * User: liuhao
 * Date: 17-8-9
 * Time: 下午3:08
 */

namespace root\modules\app\controllers;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
class TestController
{
    public function index()
    {

        $phoneUtil = PhoneNumberUtil::getInstance();

        $numberString = "";

        try {
            $numberPrototype = $phoneUtil->parse($numberString, "IN");

            echo "Input: " .          $numberString . "\n";
            echo "isValid: " .       ($phoneUtil->isValidNumber($numberPrototype) ? "true" : "false") . "\n";
            echo "E164: " .           $phoneUtil->format($numberPrototype, PhoneNumberFormat::E164) . "\n";
            echo "National: " .       $phoneUtil->format($numberPrototype, PhoneNumberFormat::NATIONAL) . "\n";
            echo "International: " .  $phoneUtil->format($numberPrototype, PhoneNumberFormat::INTERNATIONAL) . "\n";
        } catch (NumberParseException $e) {
        }
    }
}