<?php

include 'lib/Config.php';
include 'lib/Compiler.php';

$config = new Config();

if (!isset($argv[1])) {
    echo 'This command require a parameter';
    return;
}

$file = $argv[1];
$result = Compiler::compile($file, $config);

if ($result[0]) {
    echo '[OK]' . PHP_EOL;
} else {
    echo '[!!]' . PHP_EOL;
}

echo str_repeat('#', 80) . PHP_EOL;
echo $result[1] . PHP_EOL;
echo str_repeat('#', 80);
