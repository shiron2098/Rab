<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once('Rabbimq.php');
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
    const PathToDbConfigurations =__DIR__ . '/File/';

    Public $PathOfDataVendmax;
    private   $zip;
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


        $this->timestamp = strtotime('now');


    }
    public function Db_Connect(){

        /** @var array $connect */
        /** @var array SqlParamForExecuteDbStatement */
        $this->SqlParamToExecuteDbStatement = array("listOfRequests"=>array("ServiceCallInfo"=>array("CallType"=>"SQL","Sql"=>$this->SqlParam ,
            'Parameters'=> array('OfPairOfString' =>array('PairOfString' =>  'true')),
            'ProcessResultToXml'=> True,'HasResult' => true,'CompressResult'=> false,
            'accept-encoding' => 'deflate',"Sid"=>1)));

        $connect = new SoapClient(DbConnectToDAWS::WSDL,array('location' => $this->HeaderLocal, 'url' => DbConnectToDAWS::UrlNamespace,
            'trace' => TRUE,
            'exceptions' => false));

         /**AuthenticateUser @Param array  @response Object(Status,LoginType) */
        $connect->AuthenticateUser($this->ParamsToAuthenticateUser);

        /** ExecuteDbStatement @param array  @response Object(IsCompresedResponse,Response,Status,ResponseDataCompressed) @type ResponseDataCompressed = zip */
        /*sleep(5);*/
        $ToParamResponseDb= $connect->ExecuteDbStatement($this->SqlParamToExecuteDbStatement);
/*        $results2 = print_r($ToParamResponseDb,
            true);
        $this->logtext($results2);*/
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
                    $text = 'DAWS query execution failed';
                    $this->logtext($text);
                    throw new Exception($text . PHP_EOL);
                } else {
                    if(!empty($this->ResponseDB = $ToParamResponseDb->ExecuteDbStatementResult->ServiceCallResult->Response))
                    $this->boolean = 0;
                }
            }catch(Exception $e){
                echo $e->getMessage();;

            }
        }
        do
            if($this->ResponseDB !== null){
                return $this->ResponseDB;
            }while($this->Db_Connect());

    }
    /** Response Db_Connect Take out from archive */
    public function ResponseOfDbToLogFile()
    {
        $ResponseDbDaws = $this->Db_Connect();
        if (isset($ResponseDbDaws) && !empty($ResponseDbDaws)) {
            if ($this->boolean === 1) {
                $this->zip = 'zip' . rand(100,10000);
                file_put_contents($this->zip, $ResponseDbDaws);
                $zip = new ZipArchive();
                sleep(2);
                $filename = $this->zip;
                if ($zip->open($filename) === TRUE) {
                    $filename = md5(time() . rand(1, 999999)) . '.' . DbConnectToDAWS::PathToDbConfigurations;
                    $subdir1 = $filename[0];
                   $this->PathOfDataVendmax = $folder = 'File/' . $subdir1 . '/';
                    if (!file_exists($folder)) {
                        mkdir($folder, 0777, true);
                    }
                    $zip->extractTo($this->PathOfDataVendmax);
                    $zip->close();
                    $file = [
                        'timestamp' => $this->timestamp,
                        'ToMessage' => 1,
                        'PathToFile' => $this->PathOfDataVendmax];
                    $_SESSION['FileZip'] = true;
                    unlink($this->zip);
                    return $file;
                } else {
                    try {
                        throw new  Exception('Result create zip failed' . DbConnectToDAWS::NameZip);
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
                    throw new  Exception('Cannot Connect to DAWS' . DbConnectToDAWS::UrlNamespace);
                } catch (Exception $e) {
                    echo $e->getMessage();
                    $this->logtext($e->getMessage());
                }
            }
        }
    }
}
?>