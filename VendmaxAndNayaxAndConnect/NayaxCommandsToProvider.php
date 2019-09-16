<?php
require_once __DIR__ . '/../vendor/autoload.php';
use app\WorkerReceiver1;
use app\generic_command_interface;
require_once __DIR__ . "/../Interface/generic_command_interface.php";
require_once __DIR__ . '/../VendmaxAndNayaxAndConnect/DbConnectProvider.php';


class NayaxCommands extends DbConnectProvider implements generic_command_interface
{
    public $command;
    public $softwireprovider;

     public function __construct($jobid,$operatorid,$command,$software_provider)
     {
         $this->IDOperators =$operatorid;
         $this->IDJobs = $jobid;
         $this->command = $command;
         $this->softwireprovider = $software_provider;
     }

    private function ExecuteStatment()
    {
        $text = 'ExecuteStatment true';
        $this->logtext($text);
        return false;
    }
    public function get_products()
    {
        $text = 'get_products true';
        $this->logtext($text);
        return false;
    }
    public function get_customers()
    {
        $text = 'get_customers true';
        $this->logtext($text);
       return false;
    }
    public function get_pointsofsale()
    {
        $text = 'get_pointsofsale true';
        $this->logtext($text);
        return false;
    }
    public function get_locations()
    {
        $text = 'get_locations true';
        $this->logtext($text);
        return false;
    }
    public function get_non_vending_equipment(){
        $text = 'get_non_vending_equipment true';
        $this->logtext($text);
        return false;
    }

    public function get_equipment()
    {
        $text = 'get_equipment true';
        $this->logtext($text);
        return false;
    }

    public function get_items()
    {
        $text = 'get_items true';
        $this->logtext($text);
        return false;
    }

    public function get_t2s_exportPos()
    {
        $text = 'get_t2s_exportPos true';
        $this->logtext($text);
        return false;
    }

    public function get_t2s_exportPRO()
    {
        $text = 'get_t2s_exportPRO true';
        $this->logtext($text);
        return false;
    }

    public function get_t2s_exportVisits()
    {
        $text = 'get_t2s_exportVisits true';
        $this->logtext($text);
        return false;
    }

    public function get_t2s_export_pro_wbstore()
    {
        $text = 'get_t2s_export_pro_wbstore true';
        $this->logtext($text);
        return false;
    }

    public function get_t2s_export_pos_wbstore()
    {
        $text = 'get_t2s_export_pos_wbstore true';
        $this->logtext($text);
        return false;
    }
}