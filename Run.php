<?php
session_start();
include_once('CheckDataMYSQL.php');
include_once('RabbitSendSqlTakeInDbMYSQL.php');


class Run extends CheckDataMYSQL
{


    public function index()
    {
/*     $this->Tointo();
       $this->TointoJob();
       $this->TointoJob_Schedule();*/
        $data = $this->SelectToDbOperators();
        $response = $this->JobsOperators($data);
        $this->RunAndCheck($response);
    }




    public function Tointo()
    {
        $ret='24';
        $result = mysqli_query(
            $this->linkConnect,
            "insert into operators (name,code,software_provider,connection_url,user_name,user_password) values ('Anton','" . $ret  ."','http://tempuri.org/','http://web-server:8083/VmoDataAccessWS.asmx?swCode=CLASS2','Admin','00734070407B3472366F4B7A3F082408417A2278246551674B1553603A7D3D0D4105340B403F1466')"
        );
        $this->RowsNewColumnInsert();
        $result = mysqli_query(
            $this->linkConnect,
              "SELECT id FROM operators WHERE code=$ret"
        );
        $row = mysqli_fetch_assoc($result);
            $this->IDOperators = $row['id'];

    }
    public function TointoJob(){
        $result = mysqli_query(
            $this->linkConnect,
            "insert into jobs (operator_id,command) values ('" . $this->IDOperators . "','select pro.description from Products pro')"
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
    protected function RowsNewColumnInsert()
    {
        $result2 = mysqli_query(
            $this->linkConnect,
            'SELECT LAST_INSERT_ID()'
        );
        $row = mysqli_fetch_assoc($result2);
        foreach ($row as $resul) {
            return $resul;
        }
    }

}
$a = new Run();
$a->index();
?>