<?php
require_once __DIR__ . '/vendor/autoload.php';
include_once 'Rabbimq.php';


abstract class MYSQL
{
     protected $linkConnect;
    const host = 'localhost';
    const user = 'ret';
    const password = '123';
    const database = 'daws2';

    protected $DataOperators;

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
        if(!empty($id)) {
            $result = mysqli_query(
                $this->linkConnect,
                "SELECT jobs.operator_id as operatorid,jobs.id as Jobsid,name,code,software_provider,connection_url,user_name,user_password FROM operators
                  JOIN jobs on jobs.operator_id =  operators.id
                  WHERE jobs.id = $id"
            );
             $row=mysqli_fetch_assoc($result);

             $this->DataOperators = $row;
            return $this->DataOperators;

        }
    }

    protected function SelectToDbOperators()
    {
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT * FROM operators"

        );
        FOREACH ($result as $row) {
            $file[] = $row;
        }
        return $file;
    }

    protected function JobsOperators($data)
    {
        foreach ($data as $dataOperators) {
            $this->Userid = $dataOperators['id'];
            $result = mysqli_query(
                $this->linkConnect,
                "SELECT * FROM jobs WHERE operator_id = $this->Userid"
            );
            if (!empty($result)) {
                foreach ($result as $date)
                    $file[$dataOperators['name']][] = $date;
            } else {
                $a = 'error empty response';
                return $a;
            }
        }
        return $file;
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
                $this->logDB($this->IDJobs, $this->timestamp, self::statusERROR);
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

}