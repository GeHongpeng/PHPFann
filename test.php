<?php
/**
 * Created by PhpStorm.
 * User: gehongpeng
 * Date: 2016/08/09
 * Time: 13:48
 */

$value = openssl_random_pseudo_bytes(5);
var_dump($value);

$valueHex = bin2hex($value);
var_dump($valueHex);//bin2hex

$valueBin = hex2bin($valueHex);
var_dump($valueBin);
