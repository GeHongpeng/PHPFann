<?php
$mapping = array(
    'ImageSimilarityComparison\HistogramComparisonClient' => __DIR__ . '/ImageSimilarityComparison/HistogramComparison.php',
    'ImageSimilarityComparison\AHashComparisonClient' => __DIR__ . '/ImageSimilarityComparison/AHashComparison.php',
);

spl_autoload_register(function ($class) use ($mapping) {
    if (isset($mapping[$class])) {
        require $mapping[$class];
    }
}, true);