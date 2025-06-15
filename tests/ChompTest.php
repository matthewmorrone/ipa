<?php
require_once __DIR__ . '/../ipa.php';
$result = chomp('example.txt', 4);
if ($result !== 'example') {
    echo "Failed: expected 'example', got '$result'\n";
    exit(1);
}
echo "OK\n";
