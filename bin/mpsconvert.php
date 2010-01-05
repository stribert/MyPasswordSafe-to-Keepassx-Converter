#!/usr/bin/env php
<?php

require '../lib/mypasswordsafe_converter.php';

if(count($argv) < 3) {
    echo "Usage: $argv[0] PATH PATH";
    exit();
}
$mps_path = $argv[1];
$kpx_path = $argv[2];
$converter = new MypasswordsafeConverter($mps_path, $kpx_path);
$converter->convert();
