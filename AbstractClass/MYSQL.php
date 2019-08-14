<?php
require_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../AbstractClass/Rabbimq.php';

/*require_once __DIR__ . '/vendor/autoload.php';
include_once 'Rabbimq.php';*/
abstract class MYSQL
{
    const host = '127.0.0.1';
    const user = 'ret';
    const password = '123';
    const database = 'daws2';
    const Changednew = "not changed";

    protected $DataOperators;
    protected $timestamp;
    protected $linkConnect;
    protected $rows;

    public function Dbconnect()
    {
        $link = mysqli_connect(
            self::host,
            self::user,
            self::password,
            self::database
        ) or die (CheckDataMYSQL::NoConnect);
        $this->linkConnect = $link;
    }
    public function DataFromOperators($id){
        $this->Dbconnect();
        if(!empty($id)) {
            $result = mysqli_query(
                $this->linkConnect,
                "SELECT jobs.operator_id as operatorid,jobs.id as Jobsid,name,code,connection_url,user_name,user_password FROM operators
                  JOIN jobs on jobs.operator_id =  operators.id
                  WHERE jobs.operator_id = $id"
            );
             $row=mysqli_fetch_assoc($result);

             $this->DataOperators = $row;
            return $this->DataOperators;

        }
    }

    protected function SelectToDbOperatorsDAWS($operid)
    {
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT id,code,streams,name FROM operators where id = $operid"

        );
        FOREACH ($result as $row) {
            if($row['streams'] !== 0 ) {
                $arrayoper[] = $row;
                return $arrayoper;
            }
        }
    }

    public function SelectToDbOperators()
    {
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT * FROM operators"

        );
        $this->rows = $result->num_rows;
        FOREACH ($result as $row) {
            if ($row['streams'] === null || $row['streams'] == 0) {
                $arrayOperators[] = $row;
            }

        }
        return $arrayOperators;
    }

    protected function JobsOperators($data)
    {
        if (!empty($data)) {
            foreach ($data as $dataOperators) {
                $this->Userid = $dataOperators['id'];
                $result = mysqli_query(
                    $this->linkConnect,
                    "SELECT * FROM jobs WHERE operator_id = $this->Userid"
                );
                if (!empty($result)) {
                    foreach ($result as $date)
                        $arrayJobs[$dataOperators['name']][] = $date;
                } else {
                    return null;
                }
            }
            return $arrayJobs;
        }
    }
    Protected function IdOperatorsFull($idtask)
    {
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT operators.id as Operatorsid,jobs.id as Jobsid,job_schedule.job_id as JobSchedulerid FROM operators
                JOIN jobs on jobs.operator_id =  operators.id
                join job_schedule ON job_schedule.job_id = jobs.id
                WHERE jobs.id = $idtask"
        );
        try {
            if (!empty($result)) {
                foreach ($result as $row) {
                    $this->IDOperators = $row['Operatorsid'];
                    $this->IDJobs = $row['Jobsid'];
                    $this->IDJob_Scheduler = $row ['JobSchedulerid'];
                }
            } else {
                throw new Exception('error no date ID MYSQL');
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->logtext($e->getMessage());
        }
    }
    protected function UpdateJobs()
    {
        $result = mysqli_query(
            $this->linkConnect,
            "UPDATE jobs SET last_execute_dt = now() WHERE id=$this->IDJobs"
        );
        try {
            if ($result === true) {
                $a = 'Update complete timestamp to Jobs MYSQL #' . $this->IDJobs;
                return $a;
            } else {
                throw new Exception('Error update Jobs #' . $this->IDJobs);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->logtext($e->getMessage());
        }
    }
    public function time(){
        $this->timestamp =  DateTime::createFromFormat( 'U.u', sprintf('%.f', microtime(true)) )->format('Y-m-d H:i:s.u');
        return $this->timestamp;
    }
    public function Insertidrows(){
        $result2 = mysqli_query(
            $this->linkConnect,
            'SELECT LAST_INSERT_ID()'
        );
        $row = mysqli_fetch_assoc($result2);
        return $row['LAST_INSERT_ID()'];
    }
    public function SelectToDbOperatorsStreams()
    {
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT * FROM operators"

        );
        $this->rows = $result->num_rows;
        FOREACH ($result as $row) {
            if ($row['streams'] === null || $row['streams'] == 0) {
                $arrayOperators[] = $row;
                return $arrayOperators;
            }
        }
    }

    public function SelectToDbOperatorsStreamsOut(){
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT * FROM operators"

        );
        $this->rows = $result->num_rows;
        FOREACH ($result as $row) {
            if ($row['streams_response'] === null || $row['streams_response'] == 0) {
                $arrayOperators[] = $row;
            }

        }
        return $arrayOperators;
    }

}