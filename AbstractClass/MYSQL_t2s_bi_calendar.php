<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/MYSQL_t2s_bi_avg.php';


class MYSQL_t2s_bi_calendar extends MYSQL_t2s_bi_avg
{
    const missed = 2;
    const stockout = 15;
    const avg = 75;
    const items = 5;
    const distribution = 5;


    protected function daily_missed_stops_CALENDAR($dateStart)
    {
        static::DbconnectT2S_BI();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "SELECT DISTINCT * FROM Daily_Missed_Stops
                WHERE date_num = $dateStart"
        );
        $row = mysqli_fetch_assoc($result);
        if (!empty($result)) {
            foreach ($result as $date)
                if($date['missed_stops'] < static::missed){
                    $value['date']=$dateStart;
                    $value['value']='ok';
                    return $value;
                }
                else{
                    $value['date']=$dateStart;
                    $value['value']='alert';
                    return $value;
                }
        } else {
            return null;
        }
    }
    protected function daily_distribution_CALENDAR($dateStart)
    {
        static::DbconnectT2S_BI();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "SELECT DISTINCT * FROM Daily_Collection_Distribution
                WHERE date_num =$dateStart"
        );
        $row = mysqli_fetch_assoc($result);
        if (!empty($result)) {
            foreach ($result as $date)
                $array1= array(
                    'summaless_50'=> $date['less_50'],
                    'summaless_75'=> $date['more_50_less_75'],
                );
                $array2= array(
                    'summaless_50'=> $date['less_50'],
                    'summaless_75'=> $date['more_50_less_75'],
                    'summaless_100'=> $date['more_75_less_100'],
                    'summaless_150'=> $date['more_100_less_150'],
                    'summamore_150'=> $date['more_150'],
                );
                $summ1 = array_sum($array1);
                $summ2 = array_sum($array2);
                $procentandsumm2 = $summ2 / static::distribution;
                if($summ1 < $procentandsumm2) {
                    $value['date']=$dateStart;
                    $value['value']='ok';
                    return $value;
                }else {
                    $value['date']=$dateStart;
                    $value['value']='alert';
                    return $value;
                }
        } else {
            return null;
        }
    }
    protected function daily_avg_CALENDAR($dateStart)
    {
        static::DbconnectT2S_BI();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "SELECT DISTINCT * FROM Daily_Avg_Collect
                WHERE date_num  = $dateStart"
        );
        $row = mysqli_fetch_assoc($result);
        if (!empty($result)) {
            foreach ($result as $date)
                if($date['average_collect'] > static::avg){
                    $value['date']=$dateStart;
                    $value['value']='ok';
                    return $value;
                }
                else{
                    $value['date']=$dateStart;
                    $value['value']='alert';
                    return $value;
                }
        } else {
            return null;
        }
    }
    protected function daily_stockouts_CALENDAR($dateStart)
    {
        static::DbconnectT2S_BI();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "SELECT DISTINCT * FROM Daily_Stockouts_And_Not_Picked
                WHERE date_num  = $dateStart"
        );
        $row = mysqli_fetch_assoc($result);
        if (!empty($result)) {
            foreach ($result as $date)
                if($date['before_percentage'] < static::stockout){
                    $value['date']=$dateStart;
                    $value['value']='ok';
                    return $value;
                }
                else{
                    $value['date']=$dateStart;
                    $value['value']='alert';
                    return $value;
                }
        } else {
            return null;
        }
    }
    protected function daily_items_CALENDAR($dateStart)
    {
        static::DbconnectT2S_BI();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "SELECT DISTINCT * FROM Daily_Stockouts_And_Not_Picked
                WHERE date_num  = $dateStart"
        );
        $row = mysqli_fetch_assoc($result);
        if (!empty($result)) {
            foreach ($result as $date)
                if($date['after_not_picked'] < static::items){
                    $value['date']=$dateStart;
                    $value['value']='ok';
                    return $value;
                }
                else{
                    $value['date']=$dateStart;
                    $value['value']='alert';
                    return $value;
                }
        } else {
            return null;
        }
    }
    public function DeleteArrayFile($file){
        $file[] = null;
        foreach ($file as $i => $key) {
            unset($file[$i]);
        }
        return $file;
    }


}