<?php
/*$link = mysqli_connect(
    'localhost',
    'ret',
    '123',
    'daws'
) or die ('Die to die');
$results = print_r($link, true);*/
$f = fopen(__DIR__ . '/rrrs.text', 'a+');
fwrite($f, 'error' . '6564');
fclose($f);