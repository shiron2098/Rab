<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once ('CheckDataMYSQL.php');



class CreateTask extends CheckDataMYSQL
{
    protected $commands;
    protected $softwareprovider;








    public function Tointo()
    {
        $ret = '25';
        $result = mysqli_query(
            $this->linkConnect,
            "insert into operators (name,code,software_provider_id,connection_url,user_name,user_password) values ('Anton','" . $ret . "','" . $this->softwareprovider . "','http://web-server:8083/VmoDataAccessWS.asmx?swCode=CLASS2','Admin','00734070407B3472366F4B7A3F082408417A2278246551674B1553603A7D3D0D4105340B403F1466')"
        );
        $this->RowsNewColumnInsert();
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT id FROM operators WHERE id=$this->IDOperators"
        );
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT id FROM operators"
        );
        foreach ($result as $row) {
            $this->IDOperators = $row['id'];
        }

    }

    public function TointoJob()
    {


        $result = mysqli_query(
            $this->linkConnect,
            "insert into jobs (operator_id,command_id) values ('" . $this->IDOperators . "','" . $this->IDJobs . "')"
        );
        $a = $this->RowsNewColumnInsert();
        if ($a !== null) {
            $result = mysqli_query(
                $this->linkConnect,
                "SELECT id FROM jobs"
            );
            $this->IDJobs = $a;
        } else {
            return $this->IDJobs;
        }
    }

    public function TointoJob_Schedule()
    {
        $time = '5';
        $result = mysqli_query(
            $this->linkConnect,
            "insert into job_schedule (job_id,execute_interval) values ('" . $this->IDJobs . "',$time)"
        );
    }


    public function TointoCommandDetails()
    {
        $result = mysqli_query(
            $this->linkConnect,
            "insert into command_details (software_provider_id,command_id,execute_statement) values ($this->softwareprovider,$this->commands,'select * from VVI_View')"
        );
        return $this->RowsNewColumnInsert();

    }

    public function TointoCommands()
    {
        $result = mysqli_query(
            $this->linkConnect,
            "insert into commands (code,description) values ('Get VVI_View','VVI_View')"
        );
        if($result == false){
            $result = mysqli_query(
                $this->linkConnect,
            "SELECT * FROM commands WHERE code = 'Get VVI_View'"
        );
            foreach ($result as $res){
                $this->commands = $res['id'];
            }
        }else{
            return $this->RowsNewColumnInsert();
        }

    }

    public function TointoSoftware_providers()
    {
        $result = mysqli_query(
            $this->linkConnect,
            "insert into software_providers (code,description) values ('vendmax','vendmax')"
        );
        if ($result == false) {
            $result2 = mysqli_query(
                $this->linkConnect,
                "SELECT id FROM software_providers"
            );
            $row = mysqli_fetch_assoc($result2);
            $this->softwareprovider = $row['id'];
            return $this->softwareprovider;
        } else {
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
        if (empty($this->softwareprovider)) {
            foreach ($row as $resul) {
                $this->softwareprovider = $resul;
                return $this->softwareprovider;
            }
        } elseif (empty($this->IDOperators)) {
            foreach ($row as $resul) {
                $this->IDOperators = $resul;
                return $this->IDOperators;
            }
        } elseif (empty ($this->commands)) {
            foreach ($row as $resul) {
                $this->commands = $resul;
                return $this->commands;
            }
        } elseif (empty ($this->IDJobs)|| !empty($this->IDJobs)) {
            foreach ($row as $resul) {
                $this->IDJobs = $resul;
                return $this->IDJobs;
            }

        }

    }
}