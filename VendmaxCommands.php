<?php

use app\WorkerReceiver1;
use app\generic_command_interface;

require_once __DIR__ . '/vendor/autoload.php';
require_once "generic_command_interface.php";
include_once('DbConnectToDAWS.php');


class VendmaxCommands extends DbConnectToDAWS implements generic_command_interface
{
    const product = "select * from CUS_View";
    const costumer = "select top 1000 * from POS_View";
    const findfizit = "select code, cat, description, cost, in_service_date, out_service_date from products";
    const select = "select top 1000 * from POS_View";

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

    private function ExecuteStatment($SQL){
        $responseDATAMYSQL = $this->DataFromOperators($this->IDOperators);
        $this->IdOperatorsFull($this->IDJobs);
        $DAWS = new DbConnectToDAWS($SQL, $responseDATAMYSQL['connection_url'], $responseDATAMYSQL['user_name'], $responseDATAMYSQL['user_password']);
        $response = $DAWS->ResponseOfDbToLogFile();
        return $response;
    }
    public function Get_Products(){
        return $this->ExecuteStatment(VendmaxCommands::product);
    }
    public function Get_Customers(){
       return $this->ExecuteStatment(VendmaxCommands::costumer);
    }
    public function Get_VendVisits(){
        return  $this->ExecuteStatment(VendmaxCommands::findfizit);
    }
    public function Get_Select()
    {
        return  $this->ExecuteStatment(VendmaxCommands::select);
    }

}