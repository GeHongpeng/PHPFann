<?php
$mapping = array(
    'ImageSimilarityComparison\HistogramComparisonClient' => __DIR__ . '/ImageSimilarityComparison/histogram-common.inc.php',
);

spl_autoload_register(function ($class) use ($mapping) {
    if (isset($mapping[$class])) {
        require $mapping[$class];
    }
}, true);