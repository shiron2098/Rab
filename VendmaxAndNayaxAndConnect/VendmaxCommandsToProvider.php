<?php

use app\WorkerReceiver1;
use app\generic_command_interface;

require_once __DIR__ . '/../vendor/autoload.php';
require_once "Interface/generic_command_interface.php";
include_once('VendmaxAndNayaxAndConnect/DbConnectProvider.php');


class VendmaxCommands extends DbConnectProvider implements generic_command_interface
{
    const customer = "select * from CUS_View";
    const potsofsale = "select top 1000 * from POS_View";
    const product = "select * from PRO_View";
    const locations = "select top 1000 * from LOC_View";
    const non_vending_equipment = "select top 1000 * from VEQ_View";

    public $command;
    public $softwireprovider;
    public $operatorname;

     public function __construct($jobid,$operatorid,$command,$software_provider,$operatorname)
     {
         $this->operatorname = $operatorname;
         $this->IDOperators =$operatorid;
         $this->IDJobs = $jobid;
         $this->command = $command;
         $this->softwireprovider = $software_provider;
     }

    private function ExecuteStatment($SQL)
    {
        $responseDATAMYSQL = $this->DataFromOperators($this->IDOperators);
        $this->IdOperatorsFull($this->IDJobs);
        $DAWS = new DbConnectProvider($SQL, $responseDATAMYSQL['connection_url'], $responseDATAMYSQL['user_name'], $responseDATAMYSQL['user_password']);
        $response = $DAWS->ResponseOfDbToLogFile($this->operatorname,$this->command);
        return $response;
    }
    public function get_products()
    {
        return $this->ExecuteStatment(VendmaxCommands::product);
    }
    public function get_customers()
    {
       return $this->ExecuteStatment(VendmaxCommands::customer);
    }
    public function get_pointsofsale()
    {
        return  $this->ExecuteStatment(VendmaxCommands::potsofsale);
    }
    public function get_locations()
    {
        return  $this->ExecuteStatment(VendmaxCommands::locations);
    }
   public function get_non_vending_equipment(){
       return  $this->ExecuteStatment(VendmaxCommands::non_vending_equipment);
   }
}