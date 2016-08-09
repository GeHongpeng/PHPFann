<?php

$mapping = array(
    'OpensslOperation\OpensslClient' => __DIR__ . '/OpensslOperation/OpensslClient.php',
);

spl_autoload_register(function ($class) use ($mapping) {
    if (isset($mapping[$class])) {
        require $mapping[$class];
    }
}, true);
