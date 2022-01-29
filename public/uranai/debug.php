<?php
require_once __DIR__ . '/../../bootstrap/uranai.php';
$filename = public_path() . "/libs/aishou/4f_01a_dousei";

$file = new SplFileObject($filename, 'r');
while (!$file->eof()) {
    $line = $file->fgets();
    if (strlen($line) !== 50) dd(euc2utf($line), false);
}
