<?php
require __DIR__ . '/../vendor/autoload.php';

use HaleyLeoZhang\Helpers\Token;

echo PHP_EOL . PHP_EOL;
echo "---- 测试随机数 ---- 即将输出." . PHP_EOL;
echo Token::rand_str(99) . PHP_EOL;
