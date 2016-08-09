<?php

/**
 * テストコード
 */
require_once __DIR__. '/openssl/openssl-autoloader.php';

date_default_timezone_set('Asia/Tokyo');

use OpensslOperation\OpensslClient;


$image_path = './data/images.jpeg';

if (file_exists($image_path)) {
    $fp   = fopen($image_path,'rb');
    $size = filesize($image_path);
    $img  = fread($fp, $size);
    fclose($fp);

    var_dump($img);

    /*
    $imageHex = bin2hex($img);
    var_dump($imageHex);

    $imageBin = hex2bin($imageHex);
    var_dump($imageBin);
    */
}


// 暗号化と復号化用のクライアントを生成
$opensslClient = new OpensslClient();

// 暗号化キーを設定
$key = 'IEK/TEKIEK/TEKIEK/TEKIEK/TEKIEK/TEKIEK/TEKIEK/TEKIEK/TEKIEK/TEKIEK/TEKIEK/TEKIEK/TEKIEK/TEKIEK/TEKIEK/TEKIEK/TEK';
// 暗号対象データを設定
$plain_text = $img;

// 暗号化を実施
$enc = $opensslClient->encrypt($plain_text);
var_dump($enc);

// 復号化を実施
$dec = $opensslClient->decrypt($enc);
var_dump($dec);

file_put_contents('./data/result.jpg',$dec);