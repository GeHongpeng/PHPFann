<?php
/**
 * テストコード
 */
require_once __DIR__. '/ir/ir-autoloader.php';

date_default_timezone_set('Asia/Tokyo');

use ImageSimilarityComparison\HistogramComparisonClient;
use ImageSimilarityComparison\AHashComparisonClient;

// ヒストグラムから類似度を算出
$client = new HistogramComparisonClient();
$target = $client->makeHistogram('./data/image/images.jpeg');
$files = $client->enumJpeg('./data/image/');
$hisList = $client->calcIntersection($target, $files);
print_r($hisList);

// ハッシュから類似度を算出
$hashClient = new AHashComparisonClient();
$target = $hashClient->makeAHash('./data/image/images.jpeg');
$files = $hashClient->enumJpeg('./data/image/');
$hashList = $hashClient->checkHash($target, $files);
print_r($hashList);
