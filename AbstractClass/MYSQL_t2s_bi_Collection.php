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

    const productGlobalKey = 'pro_id';
    const productCode = 'pro_code';
    const productDescription = 'pro_description';
    const quantity = '2';


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
                }
                $result = mysqli_query(
                    MYSQLDataOperator::$linkConnectT2S,
                    "SELECT DISTINCT pos.pos_id as posGlobalKey,pos.cus_code as customerCode,pos.cus_description as customerDescription,pos.pos_code as posCode,pos.pos_description as posDescription,
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
                    "SELECT DISTINCT pro.pro_id as productGlobalKey,pro.pro_code as productCode,pro.pro_description as productDescription  FROM visits v
                    left join products pro on pro.pro_id = v.pos_id
                    where CONVERT (v.visit_date,date) = $date
                    ORDER BY pro.$columnsorting ASC limit $offset,$count"
                );
                $row = mysqli_fetch_assoc($result);
                if (!empty($result)) {
                    foreach ($result as $data) {
                        $dataPOS = array(
                            'productGlobalKey' => $data['productGlobalKey'],
                            'productCode' => $data['productCode'],
                            'productDescription' => $data['productDescription'],
                            'quantity' => static::quantity,
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
                    "SELECT DISTINCT pro.pro_id as productGlobalKey,pro.pro_code as productCode,pro.pro_description as productDescription  FROM visits v
                    left join products pro on pro.pro_id = v.pos_id
                    where CONVERT (v.visit_date,date) = $date
                    ORDER BY pro.$columnsorting DESC limit $offset,$count"
                );
                $row = mysqli_fetch_assoc($result);
                if (!empty($result)) {
                    foreach ($result as $data) {
                        $dataPOS = array(
                            'productGlobalKey' => $data['productGlobalKey'],
                            'productCode' => $data['productCode'],
                            'productDescription' => $data['productDescription'],
                            'quantity' => static::quantity,
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
                    "SELECT DISTINCT pro.pro_code as productCode,pro.pro_description as productDescription,pos.pos_code as posCode,pos.pos_description as posDescription,pos.cus_code as customerCode,pos.cus_description as customerDescription   FROM visits v
                    left join products pro on pro.pro_id = v.pos_id
                    left join points_of_sale pos on pos.pos_id = v.pos_id
                    where CONVERT (v.visit_date,date) = $date
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
                    "SELECT DISTINCT pro.pro_code as productCode,pro.pro_description as productDescription,pos.pos_code as posCode,pos.pos_description as posDescription,pos.cus_code as customerCode,pos.cus_description as customerDescription   FROM visits v
                    left join products pro on pro.pro_id = v.pos_id
                    left join points_of_sale pos on pos.pos_id = v.pos_id
                    where CONVERT (v.visit_date,date) = $date
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
                        $columnsorting = '80';
                        break;
                    case 'address':
                        $columnsorting = 'address_1';
                        break;
                }
                $this->DeleteArrayFile($dataPOS);
                static::DbconnectT2S_BI();
                $result = mysqli_query(
                    MYSQLDataOperator::$linkConnectT2S,
                    "SELECT DISTINCT pos.pos_code as posCode,pos.pos_description as posDescription,pos.cus_code as customerCode,pos.cus_description as customerDescription
                    ,pos.address_1,pos.address_2,pos.city,pos.state,pos.zip FROM visits v
                    left join points_of_sale pos on pos.pos_id = v.pos_id
                    where CONVERT (v.visit_date,date) = $date
                    ORDER BY $columnsorting ASC limit $offset,$count"
                );
                print_r($columnsorting);
                $row = mysqli_fetch_assoc($result);
                if (!empty($result)) {
                    foreach ($result as $data) {
                        $stringAdress = $data['zip'] . ',' . $data['state'] . ',' . $data['city'] . ',' . $data['address_1'] . ',' . $data['address_2'];
                        $dataPOS = array(
                            'posCode' => $data['posCode'],
                            'posDescription' => $data['posDescription'],
                            'customerCode' => $data['customerCode'],
                            'customerDescription' => $data['customerDescription'],
                            'collectionsValue' => '80',
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
                        $columnsorting = '80';
                        break;
                    case 'address':
                        $columnsorting = 'address_1';
                        break;
                }
                $this->DeleteArrayFile($dataPOS);
                static::DbconnectT2S_BI();
                $result = mysqli_query(
                    MYSQLDataOperator::$linkConnectT2S,
                    "SELECT DISTINCT pos.pos_code as posCode,pos.pos_description as posDescription,pos.cus_code as customerCode,pos.cus_description as customerDescription,pos.address_1,pos.address_2,pos.city,pos.state,pos.zip   FROM visits v
                    left join points_of_sale pos on pos.pos_id = v.pos_id
                    where CONVERT (v.visit_date,date) = $date
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
                            'collectionsValue' => '80',
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
                        $columnsorting = '80';
                        break;
                    case 'address':
                        $columnsorting = 'address_1';
                        break;
                }
                $this->DeleteArrayFile($dataPOS);
                switch ($maxsales){
                    case '50':
                        static::DbconnectT2S_BI();
                        $result = mysqli_query(
                            MYSQLDataOperator::$linkConnectT2S,
                            "SELECT DISTINCT pos.pos_code as posCode,pos.pos_description as posDescription,pos.cus_code as customerCode,pos.cus_description as customerDescription,pos.address_1,pos.address_2,pos.city,pos.state,pos.zip   FROM visits v
                    left join points_of_sale pos on pos.pos_id = v.pos_id
                    where CONVERT (v.visit_date,date) = $date
                    ORDER BY $columnsorting ASC limit $offset,$count"
                        );

                }
                static::DbconnectT2S_BI();
                $result = mysqli_query(
                    MYSQLDataOperator::$linkConnectT2S,
                    "SELECT DISTINCT pos.pos_code as posCode,pos.pos_description as posDescription,pos.cus_code as customerCode,pos.cus_description as customerDescription,pos.address_1,pos.address_2,pos.city,pos.state,pos.zip   FROM visits v
                    left join points_of_sale pos on pos.pos_id = v.pos_id
                    where CONVERT (v.visit_date,date) = $date
                    ORDER BY $columnsorting ASC limit $offset,$count"
                );
                print_r(MYSQLDataOperator::$linkConnectT2S);
                $row = mysqli_fetch_assoc($result);
                if (!empty($result)) {
                    foreach ($result as $data) {
                        $stringAdress = $data['zip'] . ',' . $data['state'] . ',' . $data['city'] . ',' . $data['address_1'] . ',' . $data['address_2'];
                        $dataPOS = array(
                            'posCode' => $data['posCode'],
                            'posDescription' => $data['posDescription'],
                            'customerCode' => $data['customerCode'],
                            'customerDescription' => $data['customerDescription'],
                            'collectionsValue' => '80',
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
                        $columnsorting = '80';
                        break;
                    case 'address':
                        $columnsorting = 'address_1';
                        break;
                }
                $this->DeleteArrayFile($dataPOS);
                static::DbconnectT2S_BI();
                $result = mysqli_query(
                    MYSQLDataOperator::$linkConnectT2S,
                    "SELECT DISTINCT pos.pos_code as posCode,pos.pos_description as posDescription,pos.cus_code as customerCode,pos.cus_description as customerDescription,pos.address_1,pos.address_2,pos.city,pos.state,pos.zip   FROM visits v
                    left join points_of_sale pos on pos.pos_id = v.pos_id
                    where CONVERT (v.visit_date,date) = $date
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
                            'collectionsValue' => '80',
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