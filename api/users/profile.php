<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../authentication/protectedaut.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


class profile extends protectedaut
{
    public $pdo;
    public function AUT()
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        $this->selectkey($authHeader);
        if(!empty($authHeader)) {
            if ($_SESSION['AUT'] === true) {
                $json_str = file_get_contents('php://input');
                $json_obj = json_decode($json_str);
                if(!empty($json_obj)) {
                    $this->start($json_obj);
                }else{
                    $this->selectdate();
                }
            } else {
                http_response_code(403);
            }
        }else{
            http_response_code(401);
        }
    }
    public function start($post)
    {
        if (isset($post->email) && !EMPTY($post->email)&& isset($post->newPassword)&&!empty($post->newPassword)&&!empty($post->currentPassword) && isset($post->currentPassword)) {
            if (filter_var($post->email, FILTER_VALIDATE_EMAIL)) {
                if ($text = $this->validate_password($post->newPassword) === true) {
                    $this->updateusers($post);
                } else {
                    http_response_code(422);
                    echo 'not correct new password';
                }
            } else {
                http_response_code(422);
                echo 'not correct email';
            }
        }else if(isset($post->email) && !EMPTY($post->email)&&!empty($post->currentPassword) && isset($post->currentPassword)){
            if (filter_var($post->email, FILTER_VALIDATE_EMAIL)) {
                $this->updateusersinf($post);
            }else {
                http_response_code(422);
                echo 'not correct email';
            }
        }


    }
    private function updateusers($post){
        $this->pdo = $this->DbConnectAuthencation();
        $password_hash = $this->selectPasswordHash();
        $password = password_verify($post->currentPassword, $password_hash);
        if($password === true) {
            $data=$this->selectupdate();
            if(empty($post->firstName)){
                $post->firstName = $data->firstName;
            }
            if(empty($post->lastName)){
                $post->lastName = $data->lastName;
            }
            if(empty($post->workPhone)){
                $post->workPhone = $data->workPhone;
            }
            if(empty($post->mobilePhone)){
                $post->mobilePhone = $data->mobilePhone;
            }
            try {
                $hashed_password = password_hash($post->newPassword, PASSWORD_DEFAULT);
                $data = [
                    'email' => $post->email,
                    'first_name' => $post->firstName,
                    'last_name' => $post->lastName,
                    'work_phone' => $post->workPhone,
                    'mobile_phone' => $post->mobilePhone,
                    'password_hash' => $hashed_password,
                    'user_id' => $_SESSION['USERID'],
                ];
                $sql = "update users set email= :email, first_name= :first_name,last_name=:last_name,work_phone= :work_phone,mobile_phone=:mobile_phone,password_hash=:password_hash,update_datetime_utc=now() where user_id= :user_id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($data);
                echo json_encode('Data update successfully');
            }catch (PDOException $e){
                http_response_code(422);
                echo 'error update to save data';
            }
        }else{
            http_response_code(422);
            echo 'not correct current_password';
        }
    }
    private function updateusersinf($post){
        $this->pdo = $this->DbConnectAuthencation();
        $data=$this->selectupdate();
        $password_hash = $this->selectPasswordHash();
        $password = password_verify($post->currentPassword, $password_hash);
        if($password === true) {
            if (empty($post->firstName)) {
                $post->firstName = $data->firstName;
            }
            if (empty($post->lastName)) {
                $post->lastName = $data->lastName;
            }
            if (empty($post->workPhone)) {
                $post->workPhone = $data->workPhone;
            }
            if (empty($post->mobilePhone)) {
                $post->mobilePhone = $data->mobilePhone;
            }
            try {
                $data = [
                    'email' => $post->email,
                    'first_name' => $post->firstName,
                    'last_name' => $post->lastName,
                    'work_phone' => $post->workPhone,
                    'mobile_phone' => $post->mobilePhone,
                    'user_id' => $_SESSION['USERID'],
                ];
                $sql = "update users set email= :email, first_name= :first_name,last_name=:last_name,work_phone= :work_phone,mobile_phone=:mobile_phone,update_datetime_utc=now() where user_id= :user_id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($data);
                echo json_encode('Data update successfully');
            } catch (PDOException $e) {
                http_response_code(422);
                echo 'error update to save data';
            }
        }else{
            http_response_code(422);
            echo 'not correct current_password';
        }
    }
    function validate_password($field)
    {
        if ($field == "")
            return "Не введен пароль";
        else if (strlen($field) < 8 OR strlen($field) > 32)
            return "В пароле должно быть не менее 6 символов и не более 30";
        else if (!preg_match("/^[a-zA-Z0-9\!@#\/\$%\^&\*\(\)\[\]\{\}\-=_\+\.,'\"<>\?]+$/", $field))
            return "В пароле недопустимые символы";
        return true;
    }
    private function selectdate(){
        $this->pdo = $this->DbConnectAuthencation();
        $stmt = $this->pdo->prepare("SELECT email as email,first_name as firstName,last_name as lastName,work_phone as workPhone,mobile_phone as mobilePhone FROM users u
                                      where user_id=?"
        );
        $stmt->execute(array($_SESSION['USERID']));
        $profile = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach($profile as $date);
        echo json_encode($date);
    }
    private function selectupdate(){
        $this->pdo = $this->DbConnectAuthencation();
        $stmt = $this->pdo->prepare("SELECT email as email,first_name as firstName,last_name as lastName,work_phone as workPhone,mobile_phone as mobilePhone FROM users u
                                      where user_id=?"
        );
        $stmt->execute(array($_SESSION['USERID']));
        $profile = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach($profile as $date);
        return $date;
    }
    private function selectPasswordHash()
    {
            $this->pdo = $this->DbConnectAuthencation();
            $stmt = $this->pdo->prepare("SELECT password_hash FROM users u
                                      where user_id=?"
            );
            $stmt->execute(array($_SESSION['USERID']));
            $password_hash = $stmt->fetchColumn();
            return $password_hash;

    }

}
/*$arr =(object)['email' =>'repik@mail.ru','FirstName' => 'repik','LastName' => 'repikivich','WorkPhone' => '+375254352233','MobilePhone' => '+375253335566','password'=>'Admin321','current_password'=>'Admin123'];
$a=new profile();
$a->start($arr);*/
$start = new profile();
$start->AUT();