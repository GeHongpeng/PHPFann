<?php
/**
 * テストコード
 */
require_once __DIR__. '/ir/ir-autoloader.php';

date_default_timezone_set('Asia/Tokyo');

use ImageSimilarityComparison\HistogramComparisonClient;

$client = new HistogramComparisonClient();
$target = $client->makeHistogram('./data/image/images.jpeg');
$files = $client->enumJpeg('./data/image/');

$hisList = $client->calcIntersection($target, $files);
print_r($hisList);