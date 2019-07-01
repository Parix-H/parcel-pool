<?php

header('Content-Type: application/json');

session_start();

/// instantiat a database object 
$parcelPoolDb = parcelPoolDb::getInstance();
$conn = $parcelPoolDb->getConnection();
// var_dump($conn);


/// instantiat a session object if there is not one
if (!isset($_SESSION['session_obj'])) {
    $_SESSION['session_obj'] = new sessionObj;
}


/// set variables for rate limitting feature
$ipaddress = $_SESSION['session_obj']->get_client_ip();
$sessionId = $_SERVER['HTTP_COOKIE'];
$browser = $_SERVER['HTTP_USER_AGENT'];
$requeset = $_SERVER['REQUEST_METHOD'];
$action = null;

/// check for number of visites during the last 24 hours
if ($_SESSION['session_obj']->getVisits($sessionId) > 500) {
    echo json_encode(Array('error' => 'no more that 1000 requests per 24 hrs'));
    die();
}

/// check for number of requests per second
if ($_SESSION['session_obj']->one_second() == true) {
    echo json_encode(Array('error' => 'no more than 1 request per second'));
    die();
}

///check if the refere
if ($_SESSION['session_obj']->whiteList() == false) {
    echo json_encode(Array('error' => 'not accepted http refere'));
    die();
}


///if the rate limitting feature is ok, check the requests
if (isset($_GET['page'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        if ($contentType === "application/json") {
            /// Receive the RAW post data.
            $content = trim(file_get_contents("php://input"));
            $decoded_post = json_decode($content, true);

            /// If json_decode failed, the JSON is invalid.
            if (is_array($decoded_post)) {
                switch ($_GET['page']) {

                    /// create account for a user
                    case "singup.html":
                        $valid_firstname = validate($decoded_post['firstname']);
                        $valid_lastname = validate($decoded_post['lastname']);
                        $valid_email = validate($decoded_post['email']);
                        $hashed_pass = password_hash($decoded_post['pass'], PASSWORD_DEFAULT);                            
                        $addUser = $parcelPoolDb->addUser($valid_firstname, $valid_lastname, $valid_email,$hashed_pass, $decoded_post['pass']);
                        $action = "add user";
                        if ($addUser) {
                        echo json_encode(Array("msg"=>"The user is added."));
                        }
                        break;

                    /// check the login
                    case "login.html":
                        $valid_email = validate($decoded_post['email']);
                        $hashed_pass = password_hash($decoded_post['pass'], PASSWORD_DEFAULT);
                        $user = $parcelPoolDb->allowedUser($valid_email, $decoded_post['pass']);
                        if($user['error'] !== 'Invalid') {
                            $_SESSION['login'] = true;
                            $_SESSION['userId'] = $user;
                            $action = "login";
                            echo json_encode(Array('msg'=>'WELCOME USER NUMBER '.$user));
                        } else {
                            echo json_encode(Array('msg'=>'Invalid email or password!')); 
                        }            
                        break;

                    /// search and show if a pool exist, available for public
                    case "showpools.html":
                        $existedPool = $parcelPoolDb->showPools($decoded_post['from'], $decoded_post['to']);
                        if (sizeof($existedPool) > 0) {
                            echo json_encode($existedPool);
                            $_SESSION['pool'] = true;
                            $_SESSION['from'] = $decoded_post['from'];
                            $_SESSION['to'] = $decoded_post['to'];
                            // $_SESSION['poolId'] = $existedPool[0][0];
                            $action = "search";
                        } else {
                            echo json_encode(Array('error' => 'There is no pool. Please create one.'));
                            die();
                        }
                        break;
                    
                    /// select the existed pool to add a parcel to, available for logged in users
                    case "addparcel.html":
                        if ($_SESSION['pool'] = true) {
                            if ($_SESSION['login'] = true) {
                                $valid_weight = validate($decoded_post['weight']);
                                $addedParcel = $parcelPoolDb->addParcel($_SESSION['userId'], $valid_weight, $_SESSION['poolId']);
                                echo json_encode(Array("msg"=>$addedParcel));
                                $action = "add parcel";
                            } else {
                                echo json_encode(Array('error'=>'Please login.'));
                                die();
                            }
                        } else {
                            echo json_encode(Array('error'=>'Please search for a pool or create one.'));
                            die();
                        } 
                        break;

                    /// throw an error for invalid url       
                    default:
                        $action = "signature incorrect";
                        echo json_encode(Array('error' => 'Invalid URL'));
                }
            }
        }
    } else { // method is not POST
        switch ($_GET['page']) {
            case "potentialpools.html":
            $allSources = $parcelPoolDb->listSources();
            $allDestinations = $parcelPoolDb->listDestinations();
            echo json_encode(Array($allSources,$allDestinations));
            $action = "visit";
            break;
            
            case "chosenpool.html" :
            $_SESSION['poolId'] = $_GET['id'];
            echo json_encode(Array("source"=>$_SESSION['from'], "destination"=>$_SESSION['to']));
            $action = "choose pool";
            break;

            default:
            echo json_encode(Array('error' => 'Invalid GET URL'));
        }
    }
    /// create logs for every request
    $_SESSION['session_obj']->addLog($ipaddress, $sessionId, $browser, $requeset, $action);
}




/// sanitise the posted data
function validate($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = htmlentities($data);
    return $data;
}


/*** create a class for database interactions including creating the connection, login feature, finding the pools and adding a package */
class parcelPoolDb
{

    private static $instance = null;
    private $conn;
    private $host = 'localhost';
    private $db = 'parcelpool_panel';
    private $user = 'root';
    private $pass = 'root';

    /// database connection feature, usable in other classes to connenct to the database
    private function __construct()
    {
        $this->conn = new PDO("mysql:host={$this->host}; dbname={$this->db}", $this->user, $this->pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    }
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new parcelPoolDb();
        }
        return self::$instance;
    }
    public function getConnection()
    {
        return $this->conn;
    }


    /// add new user to the database
    public function addUser($fname, $lname, $email, $pass, $passit) {
        try {
            $lastLoginId = $this->conn->lastInsertID();
            $stm = $this->conn->prepare ("INSERT INTO login (id, first_name, last_name, email, password, pass_itself) VALUES (:loginid, :fname, :lname, :email, :pass, :passit)");
            $stm->bindParam(':loginid' , $lastLoginId);
            $stm->bindParam(':fname' , $fname);
            $stm->bindParam(':lname', $lname);
            $stm->bindParam(':email', $email);
            $stm->bindParam(':pass', $pass);
            $stm->bindParam(':passit', $passit);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
                return array('error' => $e);
                die();
        }
    }


    /// look for the registered user in database
    public function allowedUser($email, $pass)
    {
        try {
            $stmt = $this->conn->prepare("SELECT id,password FROM login WHERE email=:email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $result = $stmt->fetch();
                if (password_verify($pass, $result['password'])) {
                    return $result['id'];
                } 
                else {
                    return Array('error' => 'Invalid');
                }
        } catch (PDOException $e) {
            return array('error' => $e);
            die();
        }
    }


    /// list all the source cities offer the service
    public function listSources(){
        $stm = $this->conn->prepare("SELECT source_city FROM source_city");
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /// list all the destination cities offer the service
    public function listDestinations(){
        $stm = $this->conn->prepare("SELECT destination_city FROM destination_city");
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /// look for an existed parcel-pool based on the source and destination
    public function showPools($from, $to)
    {
        $stm = $this->conn->prepare("SELECT p.id, s.source_city, d.destination_city, number_of_parcels, agent_id_id FROM parcel_pool p LEFT JOIN source_city s ON p.source_city_id = s.id LEFT JOIN destination_city d ON p.destination_city_id = d.id WHERE s.source_city = :source AND d.destination_city = :destination");
        $stm->bindParam(':source', $from);
        $stm->bindParam(':destination', $to);
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    /// show the chosen pool to add parcel
    public function chosenPool($poolId){
        try {
            $stm = $this->conn->prepare("SELECT s.source_city, d.destination_city FROM parcel_pool p INNER JOIN source_city s ON p.source_city_id = s.source_city LEFT JOIN destination_city d ON p.destination_city_id = d.destination_city WHERE p.id = :poolid");
            $stm->bindParam(':poolid', $poolId);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return array('error' => $e);
            die();
        }
    }


    /// add a parcel to the existed parcel pool
    public function addParcel($userId, $weight, $poolId) {
        try {
            $lastPackId = $this->conn->lastInsertID();
            $stm = $this->conn->prepare("INSERT INTO parcel (id, user_id, weight, pool_id) VALUES (:packId, :userId, :weight, :poolId)");
            $stm->bindParam(':packId', $lastPackId);
            $stm->bindParam(':userId', $userId);
            $stm->bindParam(':poolId', $poolId);
            $stm->bindParam(':weight', $weight);
            $stm->execute();
            return true;
        } catch (PDOException $e) {
            return array('error' => $e);
            die();
        }
    }
}
/*** end of the database class ***/


/*** create a class for sessions including logging, rate limitting and whitelisting */

class sessionObj
{
    private $conn;
    private $db;
    private $ipaddress;


    /// Logging feature that accounts for every request with IP, browser, timestamp and action  
    public function addLog($ipaddress, $sessionId, $browser, $requeset, $action)
    {
        /// create the connection to the database
        $db = parcelPoolDb::getInstance();
        $conn = $db->getConnection();
        try {
            $stmt = $conn->prepare("INSERT INTO logs(ip_address, session_id, op_sys_browser_type, request, action) VALUES (:ip ,:sessionID, :browser, :request, :action)");
            $stmt->bindParam(':ip', $ipaddress);
            $stmt->bindParam(':browser', $browser);
            $stmt->bindParam(':request', $requeset);
            $stmt->bindParam(':sessionID', $sessionId);
            $stmt->bindParam(':action', $action);
            $stmt->execute();
        }

        //if something goes wrong roll back
        catch (PDOException $ex) {
            return array('error' => $ex);
        }
    }


    /// check the refere of the request and make sure it is from localhost
    public function whiteList()
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            if (preg_match("/localhost/", $_SERVER['HTTP_REFERER'])) { //the localhost would be changed for the deployment
                return true;
            }
             else {
                return false;
            }
        }
    }


    /// counts the number of visists of a user during the last 24hours based on their sessionId
    public function getVisits($sessionId)
    {
        /// create the connection to the database
        $db = parcelPoolDb::getInstance();
        $conn = $db->getConnection();
        $stm = $conn->prepare("SELECT COUNT(*) FROM logs WHERE session_id = :session AND timestamp >= NOW()-86400 ");
        $stm->bindParam(':session', $sessionId);
        $stm->execute();
        $result = $stm->fetch();
        return $result[0];
    }

    /// check for the number of visits of a user in every second
    public function one_second()
    {
        ///when this method is called, if there is no session for the last request, it will be set as the current time
        if (!isset($_SESSION['last_request'])) {
            $_SESSION['last_request'] = time();
        } else {
            if (time() - $_SESSION['last_request'] < .5) {
                return true;
            }
        }

        /// this current time will be stored as the time of the last request and will be copmared with the next request which will be new request at that time
        $_SESSION['last_request'] = time();
    }


    // Function to get the client IP address
    public function get_client_ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}
/*** end of the session class ***/
