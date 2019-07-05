<?php
$a = fopen( 'tr', 'a+');
fwrite($a,time() . PHP_EOL);