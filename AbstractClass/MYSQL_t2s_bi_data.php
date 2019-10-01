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
    protected function daily_count_items($date)
    {
        static::DbconnectT2S_BI();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "SELECT p.pro_code as productCode,p.pro_description as productDescription,p.pro_id as productGlobalKey,n.vvs_id as quantity from not_picked_products n
	                          inner join products p
		                      on n.pro_id = p.pro_id
                              inner join points_of_sale  pos
		                          on n.pos_id = pos.pos_id
                                where CONVERT (n.visit_datetime,date) = $date
                              group by n.pro_id,n.pos_id"
        );
        $array = $result->num_rows;
        return $array;
    }
    public function DeleteArrayFile($file){
        $file[] = null;
        foreach ($file as $i => $key) {
            unset($file[$i]);
        }
        return $file;
    }
    protected function daily_count_stockouts($date)
    {
        static::DbconnectT2S_BI();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "SELECT p.pro_code as productCode,p.pro_description as productDescription,pos.pos_code as posCode,pos.pos_description as posDescription,pos.cus_code as customerCode,pos.cus_description as customerDescription from sold_out_products s
	                          inner join products p
		                      on s.pro_id = p.pro_id
                              inner join points_of_sale  pos
		                          on s.pos_id = pos.pos_id
                                where CONVERT (s.visit_date,date) = $date
                              group by s.pro_id,s.pos_id"
        );
        $array = $result->num_rows;
        return $array;
    }
    protected function daily_count_revenue($date)
    {
        static::DbconnectT2S_BI();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "select coalesce(actual_Sales_Bills, 0.00) + coalesce(actual_Sales_Coins, 0.00) as collection,p.pos_code as posCode
                         ,p.pos_description as posDescription,p.cus_code as customerCode,p.cus_description as customerDescription
                         ,p.address_1,p.address_2,p.city,p.state,p.zip
                              from visits v
                           inner join points_of_sale  p
	                      on v.pos_id = p.pos_id
                            where v.collect = 'Yes'
                    and CONVERT (v.visit_date,date) = $date"
        );
        $array = $result->num_rows;
        return $array;
    }
    protected function daily_count_destribution($date,$minsales,$maxsales)
    {
        if (isset($minsales) && isset($maxsales)) {
            static::DbconnectT2S_BI();
            $result = mysqli_query(
                MYSQLDataOperator::$linkConnectT2S,
                "select coalesce(actual_Sales_Bills, 0.00) + coalesce(actual_Sales_Coins, 0.00) as collection,p.pos_code as posCode,p.pos_id as posGlobalKey
                                    ,p.pos_description as posDescription,p.cus_code as customerCode,p.cus_description as customerDescription
                                    ,p.address_1,p.address_2,p.city,p.state,p.zip
                                    from visits v
                                    inner join points_of_sale  p
		                            on v.pos_id = p.pos_id
                                    where v.collect = 'Yes'
                    and CONVERT (v.visit_date,date) = $date
                    and coalesce(actual_Sales_Bills, 0.00) + coalesce(actual_Sales_Coins, 0.00)  between $minsales and $maxsales 
                    "
            );
            $array = $result->num_rows;
            return $array;
        } else if (isset($minsales) && !isset($maxsales) && empty($maxsales) || $minsales == '0'){
            $this->maxsales ='9999999';
            static::DbconnectT2S_BI();
            $result = mysqli_query(
                MYSQLDataOperator::$linkConnectT2S,
                "select coalesce(actual_Sales_Bills, 0.00) + coalesce(actual_Sales_Coins, 0.00) as collection,p.pos_code as posCode,p.pos_id as posGlobalKey
                                    ,p.pos_description as posDescription,p.cus_code as customerCode,p.cus_description as customerDescription
                                    ,p.address_1,p.address_2,p.city,p.state,p.zip
                                    from visits v
                                    inner join points_of_sale  p
		                            on v.pos_id = p.pos_id
                                    where v.collect = 'Yes'
                    and CONVERT (v.visit_date,date) = $date
                    and coalesce(actual_Sales_Bills, 0.00) + coalesce(actual_Sales_Coins, 0.00)  between $minsales and $this->maxsales
                    "
            );
            $array = $result->num_rows;
            return $array;
        }else if(isset($maxsales) && !isset($minsales) && empty($minsales)) {
            $this->minsales = '0';
            static::DbconnectT2S_BI();
            $result = mysqli_query(
                MYSQLDataOperator::$linkConnectT2S,
                "select coalesce(actual_Sales_Bills, 0.00) + coalesce(actual_Sales_Coins, 0.00) as collection,p.pos_code as posCode,p.pos_id as posGlobalKey
                                    ,p.pos_description as posDescription,p.cus_code as customerCode,p.cus_description as customerDescription
                                    ,p.address_1,p.address_2,p.city,p.state,p.zip
                                    from visits v
                                    inner join points_of_sale  p
		                            on v.pos_id = p.pos_id
                                    where v.collect = 'Yes'
                    and CONVERT (v.visit_date,date) = $date
                    and coalesce(actual_Sales_Bills, 0.00) + coalesce(actual_Sales_Coins, 0.00)  between $this->minsales and  $maxsales
                    "
            );
            $array = $result->num_rows;
            return $array;
        }
    }
}