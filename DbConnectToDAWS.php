<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once ('Rabbimq.php');
/*
$ad  = $aa->__getFunctions();*/
/*'userName' => 'admin',
            'userPassword' => "00734070407B3472366F4B7A3F082408417A2278246551674B1553603A7D3D0D4105340B403F1466",
            'sid' => 1,*/

class DbConnectToDAWS extends Rabbimq
{
    const WSDL = "http://web-server:8083/vmodataaccessws.asmx?WSDL";
    const HeadersLocation = "http://web-server:8083/VmoDataAccessWS.asmx?swCode=CLASS2";
    const UrlNamespace = "http://tempuri.org/";
    const NameZip = 'code.zip';
    const PathToDbConfigurations =__DIR__;
    protected $SqlParam;
    protected $ParamsToAuthenticateUser = [];
    protected $SqlParamToExecuteDbStatement = [];
    protected $ResponseDB;
    protected $boolean = 2;
    protected $HeaderLocal;
    protected $rabbi;
    protected $Username;
    protected $User_Password;
    protected $Softprovider;

    public function __construct($SqlParam,$HeadersLocal,$Username,$User_Password)
    {
/*        ini_set('session.gc_maxlifetime', 315619200);
        ini_set('session.cookie_lifetime', 315619200);*/

       if(!empty($SqlParam)&& !empty($HeadersLocal)&&!empty($Username)&&!empty($User_Password)) {
           $this->SqlParam = $SqlParam;
           $this->HeaderLocal = $HeadersLocal;
           $this->Username = $Username;
           $this->User_Password = $User_Password;
       }


        /** @var array ParamsForAuthenticateUser */
        $this->ParamsToAuthenticateUser = array(
            'userName' => $this->Username,
            'userPassword' => $this->User_Password,
            'sid' => 1,
        );


        /** @var array SqlParamForExecuteDbStatement */
        $this->SqlParamToExecuteDbStatement = array("listOfRequests"=>array("ServiceCallInfo"=>array("CallType"=>"SQL","Sql"=>$this->SqlParam ,
            'Parameters'=> array('OfPairOfString' =>array('PairOfString' =>  'true')),
            'ProcessResultToXml'=> True,'HasResult' => true,'CompressResult'=> false,
            'accept-encoding' => 'deflate',"Sid"=>1)));


        $this->timestamp = strtotime('now');


        $results = print_r($this->ParamsToAuthenticateUser,
            true);
        $results2 = print_r($this->SqlParamToExecuteDbStatement,
            true);
        $this->logtext($results);
        $this->logtext($results2);

    }
    public function Db_Connect(){

        /** @var array $connect */

        $connect = new SoapClient(DbConnectToDAWS::WSDL,array('location' => $this->HeaderLocal, 'url' => DbConnectToDAWS::UrlNamespace,
            'trace' => TRUE,
            'exceptions' => false));

         /**AuthenticateUser @Param array  @response Object(Status,LoginType) */

        $connect->AuthenticateUser($this->ParamsToAuthenticateUser);
        $results2 = print_r($connect,
            true);
        $this->logtext($results2);

        /** ExecuteDbStatement @param array  @response Object(IsCompresedResponse,Response,Status,ResponseDataCompressed) @type ResponseDataCompressed = zip */
        $ToParamResponseDb= $connect->ExecuteDbStatement($this->SqlParamToExecuteDbStatement);
        $results2 = print_r($ToParamResponseDb,
            true);
        $this->logtext($results2);
        if(!empty($ToParamResponseDb)) {
            $this->ResponseDB = $ToParamResponseDb->ExecuteDbStatementResult->ServiceCallResult->ResponseDataCompressed;
        }
        if(!empty($this->ResponseDB)){
            $this->ResponseDB;
            $this->boolean = 1;
        }
        else
        {
            try {
                if (!empty($ToParamResponseDb->ExecuteDbStatementResult->ServiceCallResult->ErrorMessage)) {
                    $this->ResponseDB = $ToParamResponseDb->ExecuteDbStatementResult->ServiceCallResult->ErrorMessage;
                    throw new Exception('Error message of db Daws' . PHP_EOL);
                } else {
                    if(!empty($this->ResponseDB = $ToParamResponseDb->ExecuteDbStatementResult->ServiceCallResult->Response))
                    $this->boolean = 0;
                }
            }catch(Exception $e){
                echo $e->getMessage();;

            }
        }
        return $this->ResponseDB;
    }
    /** Response Db_Connect Take out from archive */
    public function ResponseOfDbToLogFile()
    {
        $ResponseDbDaws = $this->Db_Connect();
        if (isset($ResponseDbDaws) && !empty($ResponseDbDaws)) {
            if ($this->boolean === 1) {
                file_put_contents(DbConnectToDAWS::NameZip, $ResponseDbDaws);
                $zip = new ZipArchive();
                $filename = DbConnectToDAWS::NameZip;
                if ($zip->open($filename) === TRUE) {
                    $zip->extractTo(DbConnectToDAWS::PathToDbConfigurations);
                    $zip->close();
                    $file = [
                        'timestamp' => $this->timestamp,
                        'ToMessage' => 1];
                    $_SESSION['FileZip'] = true;
                    unlink($filename);
                    return $file;
                } else {
                    try {
                        throw new  Exception('Failed to unzip zip' . DbConnectToDAWS::NameZip);
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                }
            }
            else if($this->boolean === 0)
            {
                $file=[
                    'timestamp' => $this->timestamp,
                    'code' => $ResponseDbDaws,
                    'ToMessage' => 0];
                return $file;
            }
            else
            {
                try {
                    throw new  Exception('Failed to connect ' . DbConnectToDAWS::UrlNamespace);
                } catch (Exception $e) {
                    echo $e->getMessage();
                    $this->logtext($e->getMessage());
                }
            }
        }
    }
}
?>