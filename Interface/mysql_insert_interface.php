<?php
namespace app;
session_start();


interface mysql_insert_interface {

    public static function ProductOut($response);
    public static function Points_of_saleOut($response);
    public static function VisitsOut($response);

}