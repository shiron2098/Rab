<?php
require_once __DIR__ . '/vendor/autoload.php';
use app\WorkerReceiver1;
use app\generic_command_interface;

require_once __DIR__ . '/vendor/autoload.php';
require_once "generic_command_interface.php";


class NayaxCommands extends DbConnectToDAWS implements generic_command_interface
{
    public $command;
    public $softwireprovider;

     public function __construct($jobid,$operatorid,$command,$software_provider)
     {
         /*parent::__construct();*/
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

}