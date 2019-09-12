<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/MYSQLDataOperator.php';

class MYSQL_t2s_bi_data extends MYSQLDataOperator
{
    protected function daily_missed_stops($datenum){
        static::DbconnectT2S_BI();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "SELECT * FROM Daily_Missed_Stops
                  WHERE date_num = $datenum"
        );
        $row = mysqli_fetch_assoc($result);
        if (!empty($result)) {
            foreach ($result as $date)
                return $date;
        } else {
            return null;
        }
    }
    protected function daily_collection_distribution($datenum){
        static::DbconnectT2S_BI();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "SELECT * FROM Daily_Collection_Distribution
                  WHERE date_num = $datenum"
        );
        $row = mysqli_fetch_assoc($result);
        if (!empty($result)) {
            foreach ($result as $date)
                return $date;
        } else {
            return null;
        }
    }
    protected function daily_avg_collect($datenum){
        static::DbconnectT2S_BI();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "SELECT * FROM Daily_Avg_Collect
                  WHERE date_num = $datenum"
        );
        $row = mysqli_fetch_assoc($result);
        if (!empty($result)) {
            foreach ($result as $date)
                return $date;
        } else {
            return null;
        }
    }
    protected function daily_stockouts_and_not_pickedhide($datenum){
        static::DbconnectT2S_BI();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "SELECT * FROM Daily_Stockouts_And_Not_Picked
                  WHERE date_num = $datenum"
        );
        $row = mysqli_fetch_assoc($result);
        if (!empty($result)) {
            foreach ($result as $date)
                return $date;
        } else {
            return null;
        }
    }
}