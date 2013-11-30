<?php

include 'lib/Config.php';
include 'lib/Compiler.php';
include 'lib/Runner.php';
include 'lib/Validator.php';

$config = new Config();

if (!isset($argv[1])) {
    echo 'This command require a parameter';
    return;
}

$file = $argv[1];
$result1 = Compiler::compile($file, $config);

if (!$result1[0]) {
    echo 'compilation error' . PHP_EOL;
    echo str_repeat('#', 80) . PHP_EOL;
    echo $result1[1] . PHP_EOL;
    echo str_repeat('#', 80);
} else {
    $result2 = Runner::run($file, $config);
    if (!$result2[0]) {
        echo 'runtime error' . PHP_EOL;
    } else {
        echo Validator::validate($file, $config);
    }
}
