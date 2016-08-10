<?php
$mapping = array(
    'ImageSimilarityComparison\HistogramComparisonClient' => __DIR__ . '/ImageSimilarityComparison/HistogramComparison.php',
);

spl_autoload_register(function ($class) use ($mapping) {
    if (isset($mapping[$class])) {
        require $mapping[$class];
    }
}, true);