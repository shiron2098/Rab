<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Interface/mysql_insert_interface.php';


use \app\mysql_insert_interface;



abstract class MYSQLDataOperator implements mysql_insert_interface
{
    const host = '127.0.0.1';
    const user = 'ret';
    const password = '123';
    const database = 't2s_bi_dashboard';
    Const NoConnect = 'NoConnect';
    const Product = 'Product';
    const Points = 'Points_of_sale';
    const Visits = 'Visits';


    private static $linkConnectT2S;

    private static function DbconnectT2S()
    {
        $link = mysqli_connect(
            self::host,
            self::user,
            self::password,
            self::database
        ) or die (MYSQLDataOperator::NoConnect);
        MYSQLDataOperator::$linkConnectT2S = $link;
    }


    public static function ProductOut($response)
    {
        MYSQLDataOperator::DbconnectT2S();
        $RESPONSE2 = html_entity_decode($response->pro_description,ENT_QUOTES);
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "insert into products (operator_id,pro_code,pro_description,pdf_code,pdf_description,pro_id,pdf_id,created_dt,batch_id) 
                               values ('" . $response->operator_id . "','" . $response->pro_code . "','" . $RESPONSE2 . "','" . $response->pdf_code . "',
                               '" . $response->pdf_description . "','" . $response->pro_id . "','" . $response->pdf_id . "','" . $response->created_dt . "','" . $response->batch_id . "')"
        );
        if($result === true){
            $text = 'String Product inserted successfully' . MYSQLDataOperator::InsertidrowsT2S();
            Log::logtextL2D($text,MYSQLDataOperator::Product);
            return $result;
        }else{
            $results2 = print_r(MYSQLDataOperator::$linkConnectT2S,
                true);
            $text = 'String Product inserted error' . $results2;
            Log::logtextL2D($text,MYSQLDataOperator::Product);
            return $result;
        }
    }

    public static function Points_of_saleOut($response)
    {
        MYSQLDataOperator::DbconnectT2S();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "insert into points_of_sale (operator_id,pos_code,pos_description,veq_code,veq_description,loc_code,loc_description,cus_code,cus_description,pos_id,veq_id,loc_id,cus_id,created_dt,batch_id) 
                               values ('" . $response->operator_id . "','" . $response->pos_code . "','" . $response->pos_description . "','" . $response->veq_code . "',
                               '" . $response->veq_description . "','" . $response->loc_code . "','" . $response->loc_description . "','" . $response->cus_code . "','" . $response->cus_description . "','" . $response->pos_id . "',
                               '" . $response->veq_id . "','" . $response->loc_id . "','" . $response->cus_id . "','" . $response->created_dt . "','" . $response->batch_id . "')"
        );
        if($result === true){
            $text = 'String POS inserted successfully' . MYSQLDataOperator::InsertidrowsT2S();
            Log::logtextL2D($text,MYSQLDataOperator::Points);
            return $result;
        }else{
            $results2 = print_r(MYSQLDataOperator::$linkConnectT2S,
                true);
            $text = 'String POS inserted error' . $results2;
            Log::logtextL2D($text,MYSQLDataOperator::Points);
            return $result;
        }
    }

    public static function VisitsOut($response)
    {
        MYSQLDataOperator::DbconnectT2S();
        if(isset($response->actual_Sales_Bills) && isset($response->actual_Sales_Coins) && !empty($response->actual_Sales_Bills) && !empty($response->actual_Sales_Coins) && isset($response->not_packed) && !empty($response->not_packed)) {
            $result = mysqli_query(
                MYSQLDataOperator::$linkConnectT2S,
                "insert into visits (operator_id,pos_id,visit_date,vvs_id,scheduled,actual_Sales_Bills,actual_Sales_Coins,col_sold_out,pro_sold_out,col_empty_after,pro_empty_after,not_packed,created_dt,batch_id) 
                               values ('" . $response->operator_id . "','" . $response->pos_id . "','" . $response->visit_date . "','" . $response->vvs_id . "',
                               '" . $response->scheduled . "','" . $response->actual_Sales_Bills . "','" . $response->actual_Sales_Coins . "','" . $response->col_sold_out . "','" . $response->pro_sold_out . "','" . $response->col_empty_after . "',
                               '" . $response->pro_empty_after . "','" . $response->not_packed . "','" . $response->created_dt . "','" . $response->batch_id . "')"
            );
            if($result === true){
                $text = 'String Visits inserted successfully' . MYSQLDataOperator::InsertidrowsT2S();
                Log::logtextL2D($text,MYSQLDataOperator::Visits);
                return $result;
            }else{
                $results2 = print_r(MYSQLDataOperator::$linkConnectT2S,
                    true);
                $text = 'String Visits inserted error' . $results2;
                Log::logtextL2D($text,MYSQLDataOperator::Visits);
                return $result;
            }
        }else {
            $result = mysqli_query(
                MYSQLDataOperator::$linkConnectT2S,
                "insert into visits (operator_id,pos_id,visit_date,vvs_id,scheduled,col_sold_out,pro_sold_out,col_empty_after,pro_empty_after,created_dt,batch_id) 
                               values ('" . $response->operator_id . "','" . $response->pos_id . "','" . $response->visit_date . "','" . $response->vvs_id . "',
                               '" . $response->scheduled . "','" . $response->col_sold_out . "','" . $response->pro_sold_out . "','" . $response->col_empty_after . "',
                               '" . $response->pro_empty_after . "','" . $response->created_dt . "','" . $response->batch_id . "')"
            );
            if($result === true){
                $text = 'String Visits inserted successfully' . MYSQLDataOperator::InsertidrowsT2S();
                Log::logtextL2D($text,MYSQLDataOperator::Visits);
                return $result;
            }else{
                $results2 = print_r(MYSQLDataOperator::$linkConnectT2S,
                    true);
                $text = 'String Visits inserted error' . $results2;
                Log::logtextL2D($text,MYSQLDataOperator::Visits);
                return $result;
            }
        }

    }
    public static function OperatorL2D($response,$provider){
        MYSQLDataOperator::DbconnectT2S();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "insert into operators (operator_name,operator_id,operator_software) 
                               values ('" . $response['name'] . "','" . $response['id'] . "','" . $provider . "')"
        );
        return $result;
    }

    public static function LogL2D($id,$command,$xml,$batchid){
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "insert into xml_log (operator_id,command_type,xml_value,batch_id) 
                               values ('" . $id . "','" . $command . "','" . $xml . "','" . $batchid. "')"
        );
        return $result;
    }

    public static function InsertidrowsT2S(){
        $result2 = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            'SELECT LAST_INSERT_ID()'
        );
        $row = mysqli_fetch_assoc($result2);
        return $row['LAST_INSERT_ID()'];
    }
}