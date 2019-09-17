<?php

use app\WorkerReceiver1;
use app\generic_command_interface;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . "/../Interface/generic_command_interface.php";
include_once __DIR__ . '/../VendmaxAndNayaxAndConnect/DbConnectProvider.php';


class VendmaxCommands extends DbConnectProvider implements generic_command_interface
{
    const customer = "select * from CUS_View";
    const potsofsale = "select top 1000 * from POS_View";
    const product = "select * from PRO_View";
    const locations = "select top 1000 * from LOC_View";
    const non_vending_equipment = "select top 1000 * from VEQ_View";
    const equipment =  'select * from VVI_View';
    const items  = 'select top 1000 * from VEQ_View';
    const exportPOS = 'exec t2s_exportPos \'No\'';
    const exportPRO = 'exec t2s_exportPro_BI \'No\'';
    const exportVISITS = 'exec t2s_exportVisits';
    const exportPRO_WbStore = 'exec t2s_exportPRO_WbStore \'No\'';
    const exportPOS_WbStore = 'exec t2s_exportPOS_WbStore \'No\'';

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

    public function get_equipment()
    {
        return  $this->ExecuteStatment(VendmaxCommands::equipment);
    }

    public function get_items()
    {
        return  $this->ExecuteStatment(VendmaxCommands::items);
    }

    public function get_t2s_exportPos()
    {
        return  $this->ExecuteStatment(VendmaxCommands::exportPOS);
    }

    public function get_t2s_exportPRO()
    {
        return  $this->ExecuteStatment(VendmaxCommands::exportPRO);
    }

    public function get_t2s_exportVisits()
    {
        return  $this->ExecuteStatment(VendmaxCommands::exportVISITS);
    }

    public function get_t2s_export_pro_wbstore()
    {
        return  $this->ExecuteStatment(VendmaxCommands::exportPRO_WbStore);
    }

    public function get_t2s_export_pos_wbstore()
    {
        return  $this->ExecuteStatment(VendmaxCommands::exportPOS_WbStore);
    }
}