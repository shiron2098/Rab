<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Interface/mysql_insert_interface.php';


use \app\mysql_insert_interface;



abstract class MYSQLDataOperator implements mysql_insert_interface
{
    const host = '127.0.0.1';
    const filepathCsv = __DIR__ . '/../VendmaxAndNayaxAndConnect/File/Csv/';
    const user = 'ret';
    const password = '123';
    const databaseT2S = 't2s_dashboard';
    const databaseT2S_BI = 't2s_bi_dashboard';
    Const NoConnect = 'NoConnect';
    const Product = 'Product';
    const Points = 'Points_of_sale';
    const Visits = 'Visits';
    const Operator = 'Operator';
    const Xml_log = 'Xml_log';

    protected static $linkConnectT2S;
    private static $boollog;

    /**
     * Dbconnect to MYSQL DB=(t2s_bi_dashboard)
     * @const host
     * @const user
     * @const password
     * @const database
     * @const NoConnect
     * @var $linkConnect
     */
    protected static function DbconnectT2S_BI()
    {
        $link = mysqli_connect(
            self::host,
            self::user,
            self::password,
            self::databaseT2S_BI
        ) or die (MYSQLDataOperator::NoConnect);
        MYSQLDataOperator::$linkConnectT2S = $link;
    }

    /**
     * insert in MYSQL product
     * @param $response
     * @return bool|mysqli_result
     */
    public static function ProductOut($response)
    {
        MYSQLDataOperator::DbconnectT2S_BI();
        $RESPONSE2 = html_entity_decode($response->pro_description,ENT_QUOTES);
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "insert into products (operator_id,pro_code,pro_description,pdf_code,pdf_description,pro_id,pdf_id,created_dt,batch_id) 
                               values ('" . $response->operator_id . "','" . $response->pro_code . "','" . $RESPONSE2 . "','" . $response->pdf_code . "',
                               '" . $response->pdf_description . "','" . $response->pro_id . "','" . $response->pdf_id . "','" . $response->created_dt . "','" . $response->batch_id . "')"
        );
        if($result === true){
            $text = 'String Product insert successfully #' . MYSQLDataOperator::InsertidrowsT2S();
            Log::logtextL2D($text,MYSQLDataOperator::Product);
            MYSQLDataOperator::$boollog = true;
            return   MYSQLDataOperator::$boollog;
        }else{
            $results2 = print_r(MYSQLDataOperator::$linkConnectT2S,
                true);
            $text = 'String Product insert error' . $results2;
            Log::logtextL2D($text,MYSQLDataOperator::Product);
            return $result;
        }
    }

    /**
     * insert in MYSQL points_of_sale
     * @param $response
     * @return bool|mysqli_result
     */
    public static function Points_of_saleOut($response)
    {
        MYSQLDataOperator::DbconnectT2S_BI();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "insert into points_of_sale (operator_id,pos_code,pos_description,veq_code,veq_description,loc_code,loc_description,cus_code,cus_description,pos_id,veq_id,loc_id,cus_id,created_dt,batch_id) 
                               values ('" . $response->operator_id . "','" . $response->pos_code . "','" . $response->pos_description . "','" . $response->veq_code . "',
                               '" . $response->veq_description . "','" . $response->loc_code . "','" . $response->loc_description . "','" . $response->cus_code . "','" . $response->cus_description . "','" . $response->pos_id . "',
                               '" . $response->veq_id . "','" . $response->loc_id . "','" . $response->cus_id . "','" . $response->created_dt . "','" . $response->batch_id . "')"
        );
        if($result === true){
            $text = 'String POS insert successfully #' . MYSQLDataOperator::InsertidrowsT2S();
            Log::logtextL2D($text,MYSQLDataOperator::Points);
            MYSQLDataOperator::$boollog = true;
            return   MYSQLDataOperator::$boollog;
        }else{
            $results2 = print_r(MYSQLDataOperator::$linkConnectT2S,
                true);
            $text = 'String POS insert error' . $results2;
            Log::logtextL2D($text,MYSQLDataOperator::Points);
            return $result;
        }
    }

    /**
     * insert in MYSQL Visits
     * @param $response
     * @return bool|mysqli_result
     */
    public static function VisitsOut($response)
    {
        MYSQLDataOperator::DbconnectT2S_BI();
        if (isset($response->actual_Sales_Bills) && isset($response->actual_Sales_Coins) && !empty($response->actual_Sales_Bills) && !empty($response->actual_Sales_Coins)&&isset($response->total_picked)) {
            $result = mysqli_query(
                MYSQLDataOperator::$linkConnectT2S,
                "insert into visits (operator_id,pos_id,visit_date,week_num,month_num,vvs_id,scheduled,serviced,collect,actual_Sales_Bills,actual_Sales_Coins,number_of_columns,col_sold_out,pro_sold_out,col_empty_after,not_picked,created_dt,batch_id) 
                               values ('" . $response->operator_id . "','" . $response->pos_id . "','" . $response->visit_date . "','" . $response->week_num . "','" . $response->month_num . "','" . $response->vvs_id . "','" . $response->scheduled . "',
                               '" . $response->serviced . "','" . $response->collect . "','" . $response->actual_Sales_Bills . "','" . $response->actual_Sales_Coins . "',
                               '" . $response->number_of_columns_before . "','" . $response->col_sold_out_before . "','" . $response->pro_sold_out_before . "',
                               '" . $response->col_empty_after . "','" . $response->total_picked . "','" . $response->created_dt . "','" . $response->batch_id . "')"
            );
            if ($result === true) {
                $text = 'String Visits insert successfully #' . MYSQLDataOperator::InsertidrowsT2S();
                Log::logtextL2D($text, MYSQLDataOperator::Visits);
                MYSQLDataOperator::$boollog = true;
                return MYSQLDataOperator::$boollog;
            } else {
                $results2 = print_r(MYSQLDataOperator::$linkConnectT2S,
                    true);
                $text = 'String Visits insert error' . $results2;
                Log::logtextL2D($text, MYSQLDataOperator::Visits);
                return $result;
            }
        }
        elseif(isset($response->actual_Sales_Bills) && isset($response->actual_Sales_Coins) && !empty($response->actual_Sales_Bills) && !empty($response->actual_Sales_Coins)&& !isset($response->total_picked)){
            $result = mysqli_query(
                MYSQLDataOperator::$linkConnectT2S,
                "insert into visits (operator_id,pos_id,visit_date,week_num,month_num,vvs_id,scheduled,serviced,collect,actual_Sales_Bills,actual_Sales_Coins,number_of_columns,col_sold_out,pro_sold_out,col_empty_after,created_dt,batch_id) 
                               values ('" . $response->operator_id . "','" . $response->pos_id . "','" . $response->visit_date . "','" . $response->week_num . "','" . $response->month_num . "','" . $response->vvs_id . "','" . $response->scheduled . "',
                               '" . $response->serviced . "','" . $response->collect . "','" . $response->actual_Sales_Bills . "','" . $response->actual_Sales_Coins . "',
                               '" . $response->number_of_columns_before . "','" . $response->col_sold_out_before . "','" . $response->pro_sold_out_before . "',
                               '" . $response->col_empty_after . "','" . $response->created_dt . "','" . $response->batch_id . "')"
            );
            if ($result === true) {
                $text = 'String Visits insert successfully #' . MYSQLDataOperator::InsertidrowsT2S();
                Log::logtextL2D($text, MYSQLDataOperator::Visits);
                MYSQLDataOperator::$boollog = true;
                return MYSQLDataOperator::$boollog;
            } else {
                $results2 = print_r(MYSQLDataOperator::$linkConnectT2S,
                    true);
                $text = 'String Visits insert error' . $results2;
                Log::logtextL2D($text, MYSQLDataOperator::Visits);
                return $result;
            }
            }else {
            if(isset($response->total_picked)&&isset($response->pro_empty_after)) {
                $result = mysqli_query(
                    MYSQLDataOperator::$linkConnectT2S,
                    "insert into visits (operator_id,pos_id,visit_date,week_num,month_num,vvs_id,scheduled,serviced,collect,number_of_columns,col_sold_out,pro_sold_out,col_empty_after,pro_empty_after,not_picked,created_dt,batch_id)
                                           values ('" . $response->operator_id . "','" . $response->pos_id . "','" . $response->visit_date . "','" . $response->week_num . "','" . $response->month_num . "',
                                           '" . $response->vvs_id . "','" . $response->scheduled . "','" . $response->serviced . "','" . $response->collect . "','" . $response->number_of_columns_before . "',
                                           '" . $response->col_sold_out_before . "','" . $response->pro_sold_out_before . "','" . $response->col_empty_after . "',
                                           '" . $response->pro_empty_after . "','" . $response->total_picked . "','" . $response->created_dt . "','" . $response->batch_id . "')"
                );
                print_R(MYSQLDataOperator::$linkConnectT2S);
                if ($result === true) {
                    $text = 'String Visits insert successfully #' . MYSQLDataOperator::InsertidrowsT2S();
                    Log::logtextL2D($text, MYSQLDataOperator::Visits);
                    MYSQLDataOperator::$boollog = true;
                    return MYSQLDataOperator::$boollog;
                } else {
                    $results2 = print_r(MYSQLDataOperator::$linkConnectT2S,
                        true);
                    $text = 'String Visits insert error' . $results2;
                    Log::logtextL2D($text, MYSQLDataOperator::Visits);
                    return $result;
                }
            }else if (isset($response->total_picked)){
                $result = mysqli_query(
                    MYSQLDataOperator::$linkConnectT2S,
                    "insert into visits (operator_id,pos_id,visit_date,week_num,month_num,vvs_id,scheduled,serviced,collect,number_of_columns,col_sold_out,pro_sold_out,col_empty_after,not_picked,created_dt,batch_id)
                                           values ('" . $response->operator_id . "','" . $response->pos_id . "','" . $response->visit_date . "','" . $response->week_num . "','" . $response->month_num . "',
                                           '" . $response->vvs_id . "','" . $response->scheduled . "','" . $response->serviced . "','" . $response->collect . "','" . $response->number_of_columns_before . "',
                                           '" . $response->col_sold_out_before . "','" . $response->pro_sold_out_before . "','" . $response->col_empty_after . "',
                                           '" . $response->total_picked . "','" . $response->created_dt . "','" . $response->batch_id . "')"
                );
                if ($result === true) {
                    $text = 'String Visits insert successfully #' . MYSQLDataOperator::InsertidrowsT2S();
                    Log::logtextL2D($text, MYSQLDataOperator::Visits);
                    MYSQLDataOperator::$boollog = true;
                    return MYSQLDataOperator::$boollog;
                } else {
                    $results2 = print_r(MYSQLDataOperator::$linkConnectT2S,
                        true);
                    $text = 'String Visits insert error' . $results2;
                    Log::logtextL2D($text, MYSQLDataOperator::Visits);
                    return $result;
                }
            }else{
                $result = mysqli_query(
                    MYSQLDataOperator::$linkConnectT2S,
                    "insert into visits (operator_id,pos_id,visit_date,week_num,month_num,vvs_id,scheduled,serviced,collect,number_of_columns,col_sold_out,pro_sold_out,col_empty_after,created_dt,batch_id)
                                           values ('" . $response->operator_id . "','" . $response->pos_id . "','" . $response->visit_date . "','" . $response->week_num . "','" . $response->month_num . "',
                                           '" . $response->vvs_id . "','" . $response->scheduled . "','" . $response->serviced . "','" . $response->collect . "','" . $response->number_of_columns_before . "',
                                           '" . $response->col_sold_out_before . "','" . $response->pro_sold_out_before . "','" . $response->col_empty_after . "',
                                           '" . $response->created_dt . "','" . $response->batch_id . "')"
                );
                if ($result === true) {
                    $text = 'String Visits insert successfully #' . MYSQLDataOperator::InsertidrowsT2S();
                    Log::logtextL2D($text, MYSQLDataOperator::Visits);
                    MYSQLDataOperator::$boollog = true;
                    return MYSQLDataOperator::$boollog;
                } else {
                    $results2 = print_r(MYSQLDataOperator::$linkConnectT2S,
                        true);
                    $text = 'String Visits insert error' . $results2;
                    Log::logtextL2D($text, MYSQLDataOperator::Visits);
                    return $result;
                }
            }
        }
    }

    /**
     * insert in MYSQL operator
     * @param $response
     * @param $provider
     * @return bool|mysqli_result
     */
    public static function OperatorL2D($response,$provider){
        MYSQLDataOperator::DbconnectT2S_BI();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "insert into operators (operator_name,operator_id,operator_software) 
                               values ('" . $response['name'] . "','" . $response['id'] . "','" . $provider . "')"
        );
        if($result === true){
            $text = 'String Operator insert successfully #' . MYSQLDataOperator::InsertidrowsT2S();
            Log::logtextL2D($text,MYSQLDataOperator::Operator);
            return $result;
        }else{
            $results2 = print_r(MYSQLDataOperator::$linkConnectT2S,
                true);
            $text = 'String Operator insert error' . $results2;
            Log::logtextL2D($text,MYSQLDataOperator::Operator);
            return $result;
        }
    }

    /**
     * insert in MYSQL xml_log
     * @param $id
     * @param $command
     * @param $xml
     * @param $batchid
     * @return bool|mysqli_result
     */
    public static function LogL2D($id,$command,$xml,$batchid){
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "insert into xml_log (operator_id,command_type,xml_value,batch_id) 
                               values ('" . $id . "','" . $command . "','" . $xml . "','" . $batchid. "')"
        );
        if($result === true){
            $text = 'String Xml_log insert successfully #' . MYSQLDataOperator::InsertidrowsT2S();
            Log::logtextL2D($text,MYSQLDataOperator::Xml_log);
            return $result;
        }else{
            $results2 = print_r(MYSQLDataOperator::$linkConnectT2S,
                true);
            $text = 'String Xml_log insert error' . $results2;
            Log::logtextL2D($text,MYSQLDataOperator::Xml_log);
            return $result;
        }
    }

    /**
     * take insert row in MYSQL
     * @return mixed
     */
    public static function InsertidrowsT2S(){
        $result2 = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            'SELECT LAST_INSERT_ID()'
        );
        $row = mysqli_fetch_assoc($result2);
        return $row['LAST_INSERT_ID()'];
    }

    public static function InsertTableT2s_dashboard($batchid){
        MYSQLDataOperator::DbconnectT2S_BI();
        $sql = "CALL usp_update_warehouse_tables('$batchid')";
        if(mysqli_query(static::$linkConnectT2S, $sql)){
            $text = "Procedure usp_update_warehouse_tables records added/updated successfully.";
            log::logtext($text);
        } else{
            $text = "ERROR: Couldn't execute " . $sql;
            log::logtext($text);
        }
    }
}