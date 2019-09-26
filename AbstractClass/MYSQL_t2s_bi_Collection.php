<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/MYSQL_t2s_bi_calendar.php';


class MYSQL_t2s_bi_Collection extends MYSQL_t2s_bi_calendar
{
    const posGlobalKey = 'pos_id';
    const customerCode = 'cus_code';
    const customerDescription = 'cus_description';
    const poscode = 'pos_code';
    const posdescription = 'pos_description';
    const distcollection = 'collection';

    private $maxsales;
    private $minsales;

    const productGlobalKey = 'pro_id';
    const productCode = 'pro_code';
    const productDescription = 'pro_description';
    const address = "address_1";
    const quantity = 'vvs_id';


    protected function daily_array_stops_collection($date, $offset, $count, $arraysorting)
    {
        $items = 0;
        $dataPOS = [];
        foreach ($arraysorting as $datasorting) {
            if ($datasorting->rule == 'ascending') {
                switch ($datasorting->field) {
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
                    case 'address':
                        $columnsorting = static::address;
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
                    ORDER BY $columnsorting ASC limit $offset,$count"
                );
                $row = mysqli_fetch_assoc($result);
                if (!empty($result)) {
                    foreach ($result as $data) {
                        $stringAdress = $data['zip'] . ',' . $data['state'] . ',' . $data['city'] . ',' . $data['address_1'] . ',' . $data['address_2'];
                        $dataPOS = array(
                            'posGlobalKey' => $data['posGlobalKey'],
                            'customerCode' => $data['customerCode'],
                            'customerDescription' => $data['customerDescription'],
                            'posCode' => $data['posCode'],
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
                switch ($datasorting->field) {
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
                    case 'address':
                        $columnsorting = static::address;
                        break;
                }
                $result = mysqli_query(
                    MYSQLDataOperator::$linkConnectT2S,
                    "SELECT DISTINCT pos.pos_id as posGlobalKey,pos.cus_code as customerCode,pos.cus_description as customerDescription,pos.pos_code as posCode,pos.pos_description as posDescription,
                    pos.address_1,pos.address_2,pos.city,pos.state,pos.zip FROM visits v
                    left join points_of_sale pos on pos.pos_id = v.pos_id
                    where CONVERT (v.visit_date,date) = $date
                    ORDER BY $columnsorting DESC limit $offset,$count"
                );
                $row = mysqli_fetch_assoc($result);
                if (!empty($result)) {
                    foreach ($result as $data) {
                        $stringAdress = $data['zip'] . ',' . $data['state'] . ',' . $data['city'] . ',' . $data['address_1'] . ',' . $data['address_2'];
                        $dataPOS = array(
                            'posGlobalKey' => $data['posGlobalKey'],
                            'customerCode' => $data['customerCode'],
                            'customerDescription' => $data['customerDescription'],
                            'posCode' => $data['posCode'],
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

    public function daily_array_items_Collection($date, $offset, $count, $arraysorting)
    {
        $items = 0;
        $dataPOS = [];
        foreach ($arraysorting as $datasorting) {
            if ($datasorting->rule == 'ascending') {
                switch ($datasorting->field) {
                    case 'productGlobalKey':
                        $columnsorting = static::productGlobalKey;
                        break;
                    case 'productCode':
                        $columnsorting = static::productCode;
                        break;
                    case 'productDescription':
                        $columnsorting = static::productDescription;
                        break;
                    case 'quantity':
                        $columnsorting = static::quantity;
                        break;
                }
                $this->DeleteArrayFile($dataPOS);
                static::DbconnectT2S_BI();
                $result = mysqli_query(
                    MYSQLDataOperator::$linkConnectT2S,
                    "SELECT p.pro_code as productCode,p.pro_description as productDescription,p.pro_id as productGlobalKey,n.vvs_id as quantity from not_picked_products n
	                          inner join products p
		                      on n.pro_id = p.pro_id
                              inner join points_of_sale  pos
		                          on n.pos_id = pos.pos_id
                                where CONVERT (n.visit_datetime,date) = $date
                              group by n.pro_id,n.pos_id
                              ORDER BY $columnsorting ASC limit $offset,$count"
                );
                $row = mysqli_fetch_assoc($result);
                if (!empty($result)) {
                    foreach ($result as $data) {
                        $dataPOS = array(
                            'productGlobalKey' => $data['productGlobalKey'],
                            'productCode' => $data['productCode'],
                            'productDescription' => $data['productDescription'],
                            'quantity' => $data['quantity'],
                        );
                        $items++;
                        $array[] = $dataPOS;
                    }
                } else {
                    return null;
                }
            } else if ($datasorting->rule == 'descending') {
                switch ($datasorting->field) {
                    case 'productGlobalKey':
                        $columnsorting = static::productGlobalKey;
                        break;
                    case 'productCode':
                        $columnsorting = static::productCode;
                        break;
                    case 'productDescription':
                        $columnsorting = static::productDescription;
                        break;
                    case 'quantity':
                        $columnsorting = static::quantity;
                        break;
                }
                $this->DeleteArrayFile($dataPOS);
                static::DbconnectT2S_BI();
                $result = mysqli_query(
                    MYSQLDataOperator::$linkConnectT2S,
                    "SELECT p.pro_code as productCode,p.pro_description as productDescription,p.pro_id as productGlobalKey,n.vvs_id as quantity from not_picked_products n
	                          inner join products p
		                      on n.pro_id = p.pro_id
                              inner join points_of_sale  pos
		                          on n.pos_id = pos.pos_id
                                where CONVERT (n.visit_datetime,date) = $date
                              group by n.pro_id,n.pos_id
                              ORDER BY $columnsorting DESC limit $offset,$count"
                );
                $row = mysqli_fetch_assoc($result);
                if (!empty($result)) {
                    foreach ($result as $data) {
                        $dataPOS = array(
                            'productGlobalKey' => $data['productGlobalKey'],
                            'productCode' => $data['productCode'],
                            'productDescription' => $data['productDescription'],
                            'quantity' => $data['quantity'],
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

    public function daily_array_stockouts_collection($date, $offset, $count, $arraysorting)
    {
        $items = 0;
        $dataPOS = [];
        foreach ($arraysorting as $datasorting) {
            if ($datasorting->rule == 'ascending') {
                switch ($datasorting->field) {
                    case 'productCode':
                        $columnsorting = static::productCode;
                        break;
                    case 'productDescription':
                        $columnsorting = static::productDescription;
                        break;
                    case 'posCode':
                        $columnsorting = static::poscode;
                        break;
                    case 'posDescription':
                        $columnsorting = static::posdescription;
                        break;
                    case 'customerCode':
                        $columnsorting = static::customerCode;
                        break;
                    case 'customerDescription':
                        $columnsorting = static::customerDescription;
                        break;
                }
                $this->DeleteArrayFile($dataPOS);
                static::DbconnectT2S_BI();
                $result = mysqli_query(
                    MYSQLDataOperator::$linkConnectT2S,
                    "SELECT p.pro_code as productCode,p.pro_description as productDescription,pos.pos_code as posCode,pos.pos_description as posDescription,pos.cus_code as customerCode,pos.cus_description as customerDescription from sold_out_products s
	                          inner join products p
		                      on s.pro_id = p.pro_id
                              inner join points_of_sale  pos
		                          on s.pos_id = pos.pos_id
                                where CONVERT (s.visit_date,date) = $date
                              group by s.pro_id,s.pos_id
                              ORDER BY $columnsorting ASC limit $offset,$count"
                );
                $row = mysqli_fetch_assoc($result);
                if (!empty($result)) {
                    foreach ($result as $data) {
                        $dataPOS = array(
                            'productCode' => $data['productCode'],
                            'productDescription' => $data['productDescription'],
                            'posCode' => $data['posCode'],
                            'posDescription' => $data['posDescription'],
                            'customerCode' => $data['customerCode'],
                            'customerDescription' => $data['customerDescription'],
                        );
                        $items++;
                        $array[] = $dataPOS;
                    }
                } else {
                    return null;
                }
            } else if ($datasorting->rule == 'descending') {
                switch ($datasorting->field) {
                    case 'productCode':
                        $columnsorting = static::productCode;
                        break;
                    case 'productDescription':
                        $columnsorting = static::productDescription;
                        break;
                    case 'posCode':
                        $columnsorting = static::productCode;
                        break;
                    case 'posDescription':
                        $columnsorting = static::productDescription;
                        break;
                    case 'customerCode':
                        $columnsorting = static::customerCode;
                        break;
                    case 'customerDescription':
                        $columnsorting = static::customerDescription;
                        break;
                }
                $this->DeleteArrayFile($dataPOS);
                static::DbconnectT2S_BI();
                    $result = mysqli_query(
                        MYSQLDataOperator::$linkConnectT2S,
                        "SELECT p.pro_code as productCode,p.pro_description as productDescription,pos.pos_code as posCode,pos.pos_description as posDescription,pos.cus_code as customerCode,pos.cus_description as customerDescription from sold_out_products s
	                          inner join products p
		                      on s.pro_id = p.pro_id
                              inner join points_of_sale  pos
		                          on s.pos_id = pos.pos_id
                                where CONVERT (s.visit_date,date) = $date
                              group by s.pro_id,s.pos_id
                              ORDER BY $columnsorting DESC limit $offset,$count"
                    );
                $row = mysqli_fetch_assoc($result);
                if (!empty($result)) {
                    foreach ($result as $data) {
                        $dataPOS = array(
                            'productCode' => $data['productCode'],
                            'productDescription' => $data['productDescription'],
                            'posCode' => $data['posCode'],
                            'posDescription' => $data['posDescription'],
                            'customerCode' => $data['customerCode'],
                            'customerDescription' => $data['customerDescription'],
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

    public function daily_array_revenue_collection($date, $offset, $count, $arraysorting)
    {
        $items = 0;
        $dataPOS = [];
        foreach ($arraysorting as $datasorting) {
            if ($datasorting->rule == 'ascending') {
                switch ($datasorting->field) {
                    case 'posCode':
                        $columnsorting = static::poscode;
                        break;
                    case 'posDescription':
                        $columnsorting = static::posdescription;
                        break;
                    case 'customerCode':
                        $columnsorting = static::customerCode;
                        break;
                    case 'customerDescription':
                        $columnsorting = static::customerDescription;
                        break;
                    case 'collectionsValue':
                        $columnsorting = static::distcollection;
                        break;
                    case 'address':
                        $columnsorting = static::address;
                        break;
                }
                $this->DeleteArrayFile($dataPOS);
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
                    and CONVERT (v.visit_date,date) = $date
                    ORDER BY $columnsorting ASC limit $offset,$count"
                );
                $row = mysqli_fetch_assoc($result);
                if (!empty($result)) {
                    foreach ($result as $data) {
                        $stringAdress = $data['zip'] . ',' . $data['state'] . ',' . $data['city'] . ',' . $data['address_1'] . ',' . $data['address_2'];
                        $dataPOS = array(
                            'posCode' => $data['posCode'],
                            'posDescription' => $data['posDescription'],
                            'customerCode' => $data['customerCode'],
                            'customerDescription' => $data['customerDescription'],
                            'collectionsValue' => $data['collection'],
                            'address' => $stringAdress,
                        );
                        $items++;
                        $array[] = $dataPOS;
                    }
                } else {
                    return null;
                }
            } else if ($datasorting->rule == 'descending') {
                switch ($datasorting->field) {
                    case 'posCode':
                        $columnsorting = static::poscode;
                        break;
                    case 'posDescription':
                        $columnsorting = static::posdescription;
                        break;
                    case 'customerCode':
                        $columnsorting = static::customerCode;
                        break;
                    case 'customerDescription':
                        $columnsorting = static::customerDescription;
                        break;
                    case 'collectionsValue':
                        $columnsorting = static::distcollection;
                        break;
                    case 'address':
                        $columnsorting = static::address;
                        break;
                }
                $this->DeleteArrayFile($dataPOS);
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
                    and CONVERT (v.visit_date,date) = $date
                    ORDER BY $columnsorting DESC limit $offset,$count"
                );
                $row = mysqli_fetch_assoc($result);
                if (!empty($result)) {
                    foreach ($result as $data) {
                        $stringAdress = $data['zip'] . ',' . $data['state'] . ',' . $data['city'] . ',' . $data['address_1'] . ',' . $data['address_2'];
                        $dataPOS = array(
                            'posCode' => $data['posCode'],
                            'posDescription' => $data['posDescription'],
                            'customerCode' => $data['customerCode'],
                            'customerDescription' => $data['customerDescription'],
                            'collectionsValue' => $data['collection'],
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
    public function daily_array_distribution_collection($date, $offset, $count, $arraysorting,$minsales,$maxsales)
    {
        $items = 0;
        $dataPOS = [];
        foreach ($arraysorting as $datasorting) {
            if ($datasorting->rule == 'ascending') {
                switch ($datasorting->field) {
                    case 'posCode':
                        $columnsorting = static::poscode;
                        break;
                    case 'posDescription':
                        $columnsorting = static::posdescription;
                        break;
                    case 'customerCode':
                        $columnsorting = static::customerCode;
                        break;
                    case 'customerDescription':
                        $columnsorting = static::customerDescription;
                        break;
                    case 'collectionsValue':
                        $columnsorting = static::distcollection;
                        break;
                    case 'address':
                        $columnsorting = 'address_1';
                        break;
                }
                $this->DeleteArrayFile($dataPOS);
                if (isset($minsales) && isset($maxsales)) {
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
                    and CONVERT (v.visit_date,date) = $date
                    and coalesce(actual_Sales_Bills, 0.00) + coalesce(actual_Sales_Coins, 0.00)  between $minsales and $maxsales 
                    ORDER BY $columnsorting ASC limit $offset,$count"
                    );
                } else if (isset($minsales) && !isset($maxsales) && empty($maxsales) || $minsales == '0'){
                    $this->maxsales ='9999999';
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
                    and CONVERT (v.visit_date,date) = $date
                    and coalesce(actual_Sales_Bills, 0.00) + coalesce(actual_Sales_Coins, 0.00)  between $minsales and $this->maxsales
                    ORDER BY $columnsorting ASC limit $offset,$count"
                );
            }else if(isset($maxsales) && !isset($minsales) && empty($minsales)) {
                    $this->minsales = '0';
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
                    and CONVERT (v.visit_date,date) = $date
                    and coalesce(actual_Sales_Bills, 0.00) + coalesce(actual_Sales_Coins, 0.00)  between $this->minsales and  $maxsales
                    ORDER BY $columnsorting ASC limit $offset,$count"
                );
            }
                $row = mysqli_fetch_assoc($result);
                if (!empty($result)) {
                    foreach ($result as $data) {
                        $stringAdress = $data['zip'] . ',' . $data['state'] . ',' . $data['city'] . ',' . $data['address_1'] . ',' . $data['address_2'];
                        $dataPOS = array(
                            'posCode' => $data['posCode'],
                            'posDescription' => $data['posDescription'],
                            'customerCode' => $data['customerCode'],
                            'customerDescription' => $data['customerDescription'],
                            'collectionsValue' => $data['collection'],
                            'address' => $stringAdress,
                        );
                        $items++;
                        $array[] = $dataPOS;
                    }
                } else {
                    return null;
                }
            } else if ($datasorting->rule == 'descending') {
                switch ($datasorting->field) {
                    case 'posCode':
                        $columnsorting = static::poscode;
                        break;
                    case 'posDescription':
                        $columnsorting = static::posdescription;
                        break;
                    case 'customerCode':
                        $columnsorting = static::customerCode;
                        break;
                    case 'customerDescription':
                        $columnsorting = static::customerDescription;
                        break;
                    case 'collectionsValue':
                        $columnsorting = static::distcollection;
                        break;
                    case 'address':
                        $columnsorting = 'address_1';
                        break;
                }
                        $this->DeleteArrayFile($dataPOS);
                        if (isset($minsales) && isset($maxsales)) {
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
                    and CONVERT (v.visit_date,date) = $date
                    and coalesce(actual_Sales_Bills, 0.00) + coalesce(actual_Sales_Coins, 0.00)  between $minsales and $maxsales 
                    ORDER BY $columnsorting DESC limit $offset,$count"
                            );
                        } else if (isset($minsales) && !isset($maxsales) && empty($maxsales) || $minsales == '0'){
                            $this->maxsales ='9999999';
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
                    and CONVERT (v.visit_date,date) = $date
                    and coalesce(actual_Sales_Bills, 0.00) + coalesce(actual_Sales_Coins, 0.00)  between $minsales and $this->maxsales
                    ORDER BY $columnsorting DESC limit $offset,$count"
                            );
                        }else if(isset($maxsales) && !isset($minsales) && empty($minsales)) {
                            $this->minsales = '0';
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
                    and CONVERT (v.visit_date,date) = $date
                    and coalesce(actual_Sales_Bills, 0.00) + coalesce(actual_Sales_Coins, 0.00)  between $this->minsales and  $maxsales
                    ORDER BY $columnsorting DESC limit $offset,$count"
                            );
                        }
                $row = mysqli_fetch_assoc($result);
                if (!empty($result)) {
                    foreach ($result as $data) {
                        $stringAdress = $data['zip'] . ',' . $data['state'] . ',' . $data['city'] . ',' . $data['address_1'] . ',' . $data['address_2'];
                        $dataPOS = array(
                            'posCode' => $data['posCode'],
                            'posDescription' => $data['posDescription'],
                            'customerCode' => $data['customerCode'],
                            'customerDescription' => $data['customerDescription'],
                            'collectionsValue' => $data['collection'],
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

}