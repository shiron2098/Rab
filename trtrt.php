<?php

/**
 * mixed object_from_file
 *
 * @param string filename
 * @return mixed
 *
 */
/*    function object_from_file($filename)
    {
        if(isset($filename) && !empty($filename)) {
            $file = file_get_contents($filename);
            $value = unserialize($file);
            return $value;
        }
    }*/

CREATE TABLE  Product (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
username VARCHAR(32),
password VARCHAR(255),
SQL VARCHAR(255),
time INTEGER,
Connection_string VARCHAR(255)
) ENGINE InnoDB DEFAULT CHARSET = UTF8;


/*$hours = time();
$rg = strtotime($hours);

print_r($hours);
if($hours >= 3600)
{
    echo 'prochel 1 chas';
}
else
{
    echo 'ne prochol 1 chas';
}

 print_r($d);*/
/*while(true){
    $data=date("i");
    if($data ==$control_time ){ действие 1}
    elseif($data ==$control_time2){действие 2}
}*/