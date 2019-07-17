<?php
session_start();
include_once('CheckDataMYSQL.php');
include_once('RabbitSendSqlTakeInDbMYSQL.php');


class Run extends CheckDataMYSQL
{
     private $row;
     private $row2;
     private $commandid;

    public function index()
    {
        $this->Tointo();
        $this->TointoCommands();
        if($this->row !== '0') {
            $this->TointoSoftware_providers();
            $this->TointoCommandDetails();
            $this->TointoJob();
            $this->TointoJob_Schedule();
        }
        $data = $this->SelectToDbOperators();
        $response = $this->JobsOperators($data);
        $this->RunAndCheck($response);
    }




    public function Tointo()
    {
        $ret = '24';
        $result = mysqli_query(
            $this->linkConnect,
            "insert into operators (name,code,software_provider,connection_url,user_name,user_password) values ('Anton','" . $ret . "','vendmax','http://web-server:8083/VmoDataAccessWS.asmx?swCode=CLASS2','Admin','00734070407B3472366F4B7A3F082408417A2278246551674B1553603A7D3D0D4105340B403F1466')"
        );
        $this->RowsNewColumnInsert();
            $result = mysqli_query(
                $this->linkConnect,
                "SELECT id FROM operators WHERE id=$this->row"
            );
            $result = mysqli_query(
                $this->linkConnect,
                "SELECT id FROM operators"
            );
            foreach ($result as $row){
                $this->IDOperators=$row['id'];
            }

    }
    public function TointoJob(){


        $result = mysqli_query(
            $this->linkConnect,
            "insert into jobs (operator_id,command_id) values ('" . $this->IDOperators . "','" . $this->commandid . "')"
        );
        $a = $this->RowsNewColumnInsert();
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT id FROM jobs"
        );
            $this->IDJobs = $a;

    }
    public function TointoJob_Schedule(){
        $time=  '10';
        $result = mysqli_query(
            $this->linkConnect,
            "insert into job_schedule (job_id,execute_interval) values ('" . $this->IDJobs . "',$time)"
        );
    }





    public function TointoCommandDetails(){
        $result = mysqli_query(
            $this->linkConnect,
            "insert into command_details (software_provider_id,command_id,execute_statement) values ($this->row,$this->row2,'SELECT pro.descriptions FROM Product pro')"
        );
        return $this->RowsNewColumnInsert();

    }
    public function TointoCommands(){
        $result = mysqli_query(
            $this->linkConnect,
            "insert into commands (code,description) values ('Get descriptiod','products')"
        );
        if($result == false){
            $result2 = mysqli_query(
                $this->linkConnect,
                "SELECT id FROM commands"
            );
            $row = mysqli_fetch_assoc($result2);
            $this->commandid = $row['id'];
            return $this->row;
        }else {
            return $this->RowsNewColumnInsert();
        }

    }
    public function TointoSoftware_providers(){
    $result = mysqli_query(
        $this->linkConnect,
        "insert into software_providers (code,description) values ('vendmax','vendmax')"
    );
    if($result == false){
        $result2 = mysqli_query(
            $this->linkConnect,
            "SELECT id FROM software_providers"
        );
        $row = mysqli_fetch_assoc($result2);
       $this->row = $row['id'];
       return $this->row;
    }else {
        return $this->RowsNewColumnInsert();
    }
}
    protected function RowsNewColumnInsert()
    {
        $result2 = mysqli_query(
            $this->linkConnect,
            'SELECT LAST_INSERT_ID()'
        );
        $row = mysqli_fetch_assoc($result2);
        if(empty($this->row2)) {
            foreach ($row as $resul) {
                $this->row2 = $resul;
                return $this->row2;
            }
        }elseif (!empty($this->row)){
            foreach ($row as $resul) {
                $this->commandid = $resul;
                return $this->commandid;
            }
        }

    }

}
$a = new Run();
$a->index();
?>