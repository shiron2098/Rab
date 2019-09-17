<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/MYSQLDataOperator.php';

class MYSQL_t2s_bi_data extends MYSQLDataOperator
{

    protected function daily_missed_stops($datenum)
    {
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

    protected function daily_collection_distribution($datenum)
    {
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

    protected function daily_avg_collect($datenum)
    {
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

    protected function daily_stockouts_and_not_picked($datenum)
    {
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

    protected function daily_revenue_per_collection($datenum)
    {
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
    protected function daily_array_revenue($datetime)
    {
        static::DbconnectT2S_BI();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "SELECT distinct date_num as date,average_collect as averageCollect FROM Daily_Avg_Collect
                  WHERE date_num <= $datetime
                  ORDER BY date_num DESC limit 7"
        );
        $row = mysqli_fetch_assoc($result);
        if (!empty($result)) {
            foreach ($result as $date)
                $array[] = $date;
        } else {
            return null;
        }
        return $array;
    }
    protected function daily_array_TableStops($date,$offset,$count){
        $items = 0;
        static::DbconnectT2S_BI();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "SELECT DISTINCT pos.pos_id,pos.cus_code,pos.cus_description,pos.pos_code,pos.pos_description FROM visits v
                    left join points_of_sale pos on pos.pos_id = v.pos_id
                    where v.visit_date = $date
                    ORDER BY v.visit_date limit $offset,$count"
        );
            $row = mysqli_fetch_assoc($result);
        if (!empty($result)) {
            foreach ($result as $date) {
                $array['date' . $items] = $date;
                $items++;
            }
        } else {
            return null;
        }
        $array['number']=$items;
        return $array;
        }
}