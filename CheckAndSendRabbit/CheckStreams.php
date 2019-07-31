<?php
require_once __DIR__ . '/vendor/autoload.php';

include_once('CheckDataMYSQL.php');

class CheckStreams extends CheckDataMYSQL
{
       private $timetaskcheck;


       public function CheckDouble (){
           $this->UpdateOperStreams();
       }
      public function Check(){
          $data = $this->CheckStreamsSelect();
          if(!empty($data)) {
              foreach ($data as $row) {
                  $this->IDOperators=$row['id'];
                  if ($row['streams'] == 2) {
                      $this->CheckDouble();
                  }

                  $response = $this->JobsOperators($data);
                  foreach ($response as $arrayTime) {
                      foreach ($arrayTime as $data) {
                          $this->IDJobs=$data['id'];
                          $this->IDOperators= $data['operator_id'];
                          $this->Checktime();
                          if ($this->timestamp > $this->timetaskcheck) {
                              $this->UpdateOperStreams();
                              break;

                          } else {
                              $text = 'Streams Ok';
                              $this->logtext($text);
                          }
                      }
                  }
              }
          }else
          {
              return null;
          }
      }
      public function Checktime(){
          $result = mysqli_query(
              $this->linkConnect,
              "SELECT last_execute_dt FROM jobs WHERE id = $this->IDJobs"
          );
          $row = mysqli_fetch_assoc($result);
          foreach ($row as $response) ;
          $TimeToUnix = strtotime($response);
          $this->timetaskcheck = Date('Y-m-d H:i:s',strtotime('+5 minutes',$TimeToUnix));
      }


}
