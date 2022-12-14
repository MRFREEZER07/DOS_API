<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require_once("REST.api.php");
require_once("lib/Database.class.php");
require_once("lib/Signup.class.php");
require_once("lib/Auth.class.php");
require_once("lib/User.class.php");
require_once "lib/Functionality.class.php";
require_once "lib/UserMonitor.class.php";
class API extends REST
{
    public $data = "";
    private $current_call;
    private $auth;

    private $db = null;


    public function __construct()
    {
        parent::__construct();                                       // Init parent contructor
        $this->db = Database::get_connection();                    // Initiate Database connection
    }



    /*
         * Public method for access api.
         * This method dynmically call the method based on the query string
         *
         */


    public function die($e)
    {
        $data = [
                "error" => $e->getMessage()
            ];
        $response_code = 400;
        if ($e->getMessage() == "Expired token" || $e->getMessage() == "Unauthorized") {
            $response_code = 403;
        }
    
        if ($e->getMessage() == "Not found") {
            $response_code = 404;
        }
        $data = $this->json($data);
        $this->response($data, $response_code);
    }
    
    public function isAuthenticated()
    {
        if ($this->auth ==null) {
            return false;
        }
        if ($this->auth->getOAuth()->authenticate() and isset($_SESSION['username'])) {
            return true;
        } else {
            return false;
        }
    }
    public function getUsername()
    {
        return $_SESSION['username'];
    }

    public function processApi()
    {
        $func = strtolower(trim(str_replace("/", "", $_REQUEST['rquest'])));
        if ((int)method_exists($this, $func) > 0) {
            $this->$func();
        } else {
            if (isset($_GET['namespace'])) {
                $dir = $_SERVER['DOCUMENT_ROOT'].'/api/apis/'.$_GET['namespace'];
                $file = $dir.'/'.$func.'.php';
                //print($file);
                if (file_exists($file)) {
                    include $file;
                    $this->current_call = Closure::bind(${$func}, $this, get_class());
                    $this->$func();
                } else {
                    $this->response($this->json(['error'=>'method_not_found']), 404);
                }
    
                /**
                 * Use the following snippet if you want to include multiple files
                 */
                    // $methods = scandir($dir);
                    // //var_dump($methods);
                    // foreach($methods as $m){
                    //     if($m == "." or $m == ".."){
                    //         continue;
                    //     }
                    //     $basem = basename($m, '.php');
                    //     //echo "Trying to call $basem() for $func()\n";
                    //     if($basem == $func){
                    //         include $dir."/".$m;
                    //         $this->current_call = Closure::bind(${$basem}, $this, get_class());
                    //         $this->$basem();
                    //     }
                    // }
            } else {
                //we can even process functions without namespace here.
                $this->response($this->json(["namespace"=>$_GET,'error'=>'method_not_found']), 404);
            }
        }
    }

    public function auth()
    {
        $headers =getallheaders();
        if (isset($headers['Authorization'])) {
            $token =explode(' ', $headers['Authorization']); //BREAKES THE BAREER FROM THE STRING
            $this->auth =new Auth($token[1]);
        }
    }
    
    

    public function __call($method, $args)
    {
        if (is_callable($this->current_call)) {
            return call_user_func_array($this->current_call, $args);
        } else {
            $this->response($this->json(['error' => 'method_not_callable']), 404);
        }
    }



    /*************API SPACE START*******************/

    private function about()
    {
        if ($this->get_request_method() != "POST") {
            $error = array('method'=>$this->get_request_method(),'status' => 'WRONG_CALL', "msg" => "The type of call cannot be accepted by our servers.");
            $error = $this->json($error);
            $this->response($error, 406);
        }
        $data = array('method'=>$this->get_request_method(),'version' => $this->_request['version'], 'desc' => 'This API is created by Blovia Technologies Pvt. Ltd., for the public usage for accessing data about vehicles.');
        $data = $this->json($data);
        $this->response($data, 200);
    }




    public function test()
    {
        $data = $this->json(getallheaders());
        $this->response($data, 200);
    }


    public function generate_hash()
    {
        $bytes = random_bytes(16);
        return bin2hex($bytes);
    }

    private function gen_hash()
    {
        if (isset($this->_request['pass'])) {
            $s = new Signup("", $this->_request['pass'], "");
            $hash = $s->hashPassword();
            $data = [
                "hash" => $hash,
                "val" => $this->_request['pass'],
                "verify" => password_verify($this->_request['pass'], $hash)
            ];
            $data = $this->json($data);
            $this->response($data, 200);
        }
    }

  



    /*************API SPACE END*********************/

    /*
            Encode array into JSON
        */
    private function json($data)
    {
        if (is_array($data)) {
            return json_encode($data, JSON_PRETTY_PRINT);
        } else {
            return "{}";
        }
    }
}

// Initiiate Library

$api = new API;
try {
    $api->auth();
    $api->processApi();
} catch (Exception $e) {
    $api->die($e);
}
