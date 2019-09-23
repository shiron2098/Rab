<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../AbstractClass/Rabbimq.php';
ini_set('default_socket_timeout', 1000);

class DbConnectProvider extends Rabbimq
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

        while (openssl_error_string()) {}
        /** @var array $connect */
        /** @var array SqlParamForExecuteDbStatement */
        $this->SqlParamToExecuteDbStatement = array("listOfRequests"=>array("ServiceCallInfo"=>array("CallType"=>"SQL","Sql"=> $this->SqlParam ,
            'Parameters'=> array('OfPairOfString' =>array('PairOfString' =>  'true')),
            'ProcessResultToXml'=> true,'HasResult' => true,'CompressResult'=> false,
            'accept-encoding' => 'deflate',"Sid"=>1)));

        try {
            $connect = new SoapClient(DbConnectProvider::WSDL, array('location' => $this->HeaderLocal, 'url' => DbConnectProvider::UrlNamespace,
                'trace' => true,
                'exceptions' => false,
            ));
        } catch (SoapFault $e){
            $e->getMessage();
        }

        /**AuthenticateUser
         * @Param array
         * @response Object(Status,LoginType)
         */
        $connect->AuthenticateUser($this->ParamsToAuthenticateUser);

        /** ExecuteDbStatement
         * @param array
         * @response Object(IsCompresedResponse,Response,Status,ResponseDataCompressed)
         * @type ResponseDataCompressed = zip
         */
        sleep(2);
        $ToParamResponseDb= $connect->ExecuteDbStatement($this->SqlParamToExecuteDbStatement);
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
                    $results2 = print_r($this->ResponseDB,
                        true);
                    $this->logtext($results2);
                    throw new Exception($text . PHP_EOL);
                } else {
                    if(!empty($this->ResponseDB = $ToParamResponseDb->ExecuteDbStatementResult->ServiceCallResult->Response))
                    $this->boolean = 0;
                }
            }catch(Exception $e){
                echo $e->getMessage();
            }
        }

/*            if($this->ResponseDB !== null){
                return $this->ResponseDB;
            }else{
                $this->Db_Connect();
            }*/
     do
            if($this->ResponseDB !== null){
                return $this->ResponseDB;
            }while($this->Db_Connect());

    }
    /** Response Db_Connect Take out from archive */
    public function ResponseOfDbToLogFile($operatorname,$command)
    {
        $ResponseDbDaws = $this->Db_Connect();
        if (isset($ResponseDbDaws) && !empty($ResponseDbDaws)) {
            if ($this->boolean === 1) {
                $this->zip = 'zip' . rand(100,10000);
                file_put_contents($this->zip, $ResponseDbDaws);
                $zip = new ZipArchive();
                $filename = $this->zip;
                if ($zip->open($filename) === TRUE) {
/*                    $filename = md5(time() . rand(1, 999999)) . '.' . DbConnectProvider::PathToDbConfigurations;
                    $subdir1 = $filename[0];*/
                   $this->PathOfDataVendmax = $folder = __DIR__ . '/File/' . $operatorname . '/' . $command  . '/';
                    if (!file_exists($folder)) {
                        mkdir($folder, 0777, true);
                    }
                    $zip->extractTo($this->PathOfDataVendmax);
                    $zip->close();
                    $arrayOptionsFromZip = [
                        'timestamp' => $this->timestamp,
                        'ToMessage' => 1,
                        'PathToFile' => $this->PathOfDataVendmax];
                    $_SESSION['FileZip'] = true;
                    unlink($this->zip);
                    return $arrayOptionsFromZip;
                } else {
                    try {
                        throw new  Exception('Result create zip failed' . DbConnectProvider::NameZip);
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                }
            }
            else if($this->boolean === 0)
            {
                $arrayOptionsFromProviderCodeResponse=[
                    'timestamp' => $this->timestamp,
                    'code' => $ResponseDbDaws,
                    'ToMessage' => 0];
                return $arrayOptionsFromProviderCodeResponse;
            }
            else
            {
                try {
                    throw new  Exception('Cannot Connect to DAWS' . DbConnectProvider::UrlNamespace);
                } catch (Exception $e) {
                    echo $e->getMessage();
                    $this->logtext($e->getMessage());
                }
            }
        }
    }
}
/*$a = new DbConnectProvider('exec t2s_exportPos No',DbConnectProvider::HeadersLocation,'admin','00734070407B3472366F4B7A3F082408417A2278246551674B1553603A7D3D0D4105340B403F1466');
$a->ResponseOfDbToLogFile('1','exec t2s_exportPos No')*/
?>