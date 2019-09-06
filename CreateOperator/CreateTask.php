<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../CheckAndSendRabbitMYSQL/CheckDataMYSQL.php';



class CreateTask extends CheckDataMYSQL
{
    Public $commands;
    Public $softwareprovider;

    public function Tointo()
    {
        $ret = '29';
        $result = mysqli_query(
            $this->linkConnect,
            "insert into operators (name,code,software_provider_id,connection_url,streams,user_name,user_password) values ('CLASS2','" . $ret . "','" . $this->softwareprovider . "','http://web-server:8083/VmoDataAccessWS.asmx?swCode=vmsasha3',0,'Admin','00734070407B3472366F4B7A3F082408417A2278246551674B1553603A7D3D0D4105340B403F1466')"
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
            "insert into jobs (operator_id,command_id) values ('" . $this->IDOperators . "','" . $this->commands . "')"
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

    public function TointoCommands()
    {
        $result = mysqli_query(
            $this->linkConnect,
            "insert into commands (code,description) values ('exec t2s_exportPRO No','t2s_exportPRO')"
        );
        if($result == false){
            $result = mysqli_query(
                $this->linkConnect,
            "SELECT * FROM commands WHERE code = 'exec t2s_exportPRO No'"
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
            "insert into software_providers (code,description) values ('Vendmax','Vendmax')"
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

    public function Select()
    {
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT * FROM operators"

        );
        $this->rows = $result->num_rows;
        FOREACH ($result as $row) {
            $arrayOperators[] = $row;

        }
        return $arrayOperators;
    }
}
/*$CREATE = NEW CreateTask();
$CREATE->TointoSoftware_providers();
$CREATE->Tointo();
$CREATE->TointoCommands();
        if ($CREATE->commands !== '0' && $CREATE->commands !== null) {
            $CREATE->TointoJob();
            $CREATE->TointoJob_Schedule();
        }*/