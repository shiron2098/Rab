<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/MYSQLDataOperator.php';

class MYSQL_t2s_bi_data extends MYSQLDataOperator
{
    const posGlobalKey = 'pos_id';
    const customerCode = 'cus_code';
    const customerDescription = 'cus_description';
    const poscode = 'pos_code';
    const posdescription = 'pos_description';

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
    protected function daily_array_TableStops($date,$offset,$count,$arraysorting)
    {
        $items = 0;
        $dataPOS = [];
        foreach($arraysorting as $datasorting) {
            if ($datasorting->rule == 'ascending') {
                switch ($datasorting->field){
                    case 'posGlobalKey':
                        $columnsorting = static::posGlobalKey;
                        break;
                    case 'customerCode':
                        $columnsorting = static::customerCode;
                        break;
                    case 'customerDescription':
                        $columnsorting = static::customerDescription;
                        break;
                    case 'posCode':
                        $columnsorting = static::poscode;
                        break;
                    case 'posDescription':
                        $columnsorting = static::posdescription;
                        break;
                }
                $this->DeleteArrayFile($dataPOS);
                static::DbconnectT2S_BI();
                $result = mysqli_query(
                    MYSQLDataOperator::$linkConnectT2S,
                    "SELECT DISTINCT pos.pos_id as posGlobalKey,pos.cus_code as customerCode,pos.cus_description as customerDescription,pos.pos_code as posCode,pos.pos_description as posDescription,
                    pos.address_1,pos.address_2,pos.city,pos.state,pos.zip FROM visits v
                    left join points_of_sale pos on pos.pos_id = v.pos_id
                    where CONVERT (v.visit_date,date) = $date
                    ORDER BY pos.$columnsorting ASC limit $offset,$count"
                );
                $row = mysqli_fetch_assoc($result);
                if (!empty($result)) {
                    foreach ($result as $data) {
                        $stringAdress = $data['zip'] . ',' . $data['state'] . ',' . $data['city'] . ',' . $data['address_1'] . ',' . $data['address_2'];
                        $dataPOS = array(
                            'posGlobalKey' => $data['posGlobalKey'],
                            'customerCode' => $data['customerCode'],
                            'customerDescription' => $data['customerDescription'],
                            'PosCode' => $data['posCode'],
                            'posDescription' => $data['posDescription'],
                            'address' => $stringAdress,
                        );
                        $items++;
                        $array[] = $dataPOS;
                    }
                } else {
                    return null;
                }
            } else if ($datasorting->rule == 'descending') {
                $this->DeleteArrayFile($dataPOS);
                static::DbconnectT2S_BI();
                switch ($datasorting->field){
                    case 'posGlobalKey':
                        $columnsorting = static::posGlobalKey;
                        break;
                    case 'customerCode':
                        $columnsorting = static::customerCode;
                        break;
                    case 'customerDescription':
                        $columnsorting = static::customerDescription;
                        break;
                    case 'posCode':
                        $columnsorting = static::poscode;
                        break;
                    case 'posDescription':
                        $columnsorting = static::posdescription;
                        break;
                }
                $result = mysqli_query(
                    MYSQLDataOperator::$linkConnectT2S,
                    "SELECT DISTINCT pos.pos_id as posGlobalKey,pos.cus_code as customerCode,pos.cus_description as customerDescription,pos.pos_code as PosCode,pos.pos_description as posDescription,
                    pos.address_1,pos.address_2,pos.city,pos.state,pos.zip FROM visits v
                    left join points_of_sale pos on pos.pos_id = v.pos_id
                    where CONVERT (v.visit_date,date) = $date
                    ORDER BY pos.$columnsorting DESC limit $offset,$count"
                );
                $row = mysqli_fetch_assoc($result);
                if (!empty($result)) {
                    foreach ($result as $data) {
                        $stringAdress = $data['zip'] . ',' . $data['state'] . ',' . $data['city'] . ',' . $data['address_1'] . ',' . $data['address_2'];
                        $dataPOS = array(
                            'posGlobalKey' => $data['posGlobalKey'],
                            'customerCode' => $data['customerCode'],
                            'customerDescription' => $data['customerDescription'],
                            'PosCode' => $data['posCode'],
                            'posDescription' => $data['posDescription'],
                            'address' => $stringAdress,
                        );
                        $items++;
                        $array[] = $dataPOS;
                    }
                } else {
                    return null;
                }
            }
        }
        return $array;
    }
    protected function daily_count_POS($date)
    {
        static::DbconnectT2S_BI();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "SELECT count(DISTINCT pos.pos_id) FROM visits v
                    left join points_of_sale pos on pos.pos_id = v.pos_id
                    where CONVERT (v.visit_date,date) = $date"
        );
        $row = mysqli_fetch_assoc($result);
        if (!empty($result)) {
            foreach ($result as $date) {
                foreach($date as $item) {
                    $array = $item;
                }
            }
        } else {
                return null;
            }
            return $array;
    }
    public function DeleteArrayFile($file){
        $file[] = null;
        foreach ($file as $i => $key) {
            unset($file[$i]);
        }
        return $file;
    }
}