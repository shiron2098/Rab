<?php
namespace app;




interface generic_command_interface{

    public function get_products();
    public function get_customers();
    public function get_pointsofsale();
    public function get_locations();
}