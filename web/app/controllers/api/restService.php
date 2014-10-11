<?php

abstract class restService {

    protected $_serviceName;
    private $_requestStatus = array(
        ResponseCode::OK => 'OK',
        ResponseCode::NOT_FOUND => 'Not Found',
        ResponseCode::METHOD_NOT_ALLOWED => 'Method Not Allowed',
        ResponseCode::INT_SERVER_ERROR => 'Internal Server Error',
    );
    
    private $_function = '';

    /**
     * Property: method
     * The HTTP method this request was made in, either GET, POST, PUT or DELETE
     */
    protected $method = '';

    /**
     * Property: endpoint
     * The Model requested in the URI. eg: /files
     */
    protected $endpoint = '';

    /**
     * Property: verb
     * An optional additional descriptor about the endpoint, used for things that can
     * not be handled by the basic methods. eg: /files/process
     */
    protected $verb = '';

    /**
     * Property: args
     * Any additional URI components after the endpoint and verb have been removed, in our
     * case, an integer ID for the resource. eg: /<endpoint>/<verb>/<arg0>/<arg1>
     * or /<endpoint>/<arg0>
     */
    protected $args = Array();

    /**
     * Property: file
     * Stores the input of the PUT request
     */
    protected $file = Null;

    protected abstract function _setServiceName();

    static function __callStatic($name, $arguments) {
        $return = false;
        $class = get_called_class();

        if ($name == '_' . $class) {
            $return = new $class($arguments);
        }
        return $return;
    }

    private function _loadConstants() {
        $obj = new ReflectionClass($this);
        $fn = $obj->getFileName();
        if ($fn) {
            define('PAGE_NAME', basename($fn, '.php'));
            define('PAGE_PATH', substr($fn, strrpos($fn, APP_PATH . 'controllers') + strlen(APP_PATH . 'controllers'), -strlen('.php')));
        }
        return $fn;
    }

    public function __construct($params) {
        if(!empty($params)){$this->_function = $params[0];}
        $this->_loadConstants();
        $this->_setServiceName();

        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");
        
        $this->_getHTTPMethod();
        $this->_performRequest();

        
    }

    private function callMethod($payload) {
        $methodToCall = $this->_getMethodName($this->method, $this->_function);
        if (method_exists($this, $methodToCall)) {
            try{
                $this->{$methodToCall}($payload);
            }
            catch(Exception $e){
                $this->_generateResponse('Error', ResponseCode::INT_SERVER_ERROR);
            }
        } else {
            echo 'Undefined method';
        }
    }

    //autogenerate method name to call after receiving cURL request
    private function _getMethodName($method, $name) {
        $methodType = strtolower($method); //delete/put
        $methodName = ucfirst($name);
        return $methodType . $methodName; //eg: deleteUser
    }

    public function _generateResponse($data, $status = ResponseCode::OK) {
        header("HTTP/1.1 " . $status . " " . $this->_getRequestStatusDescr($status));
        return json_encode($data);
    }

    private function _cleanInputs($data) {
        $clean_input = Array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->_cleanInputs($v);
            }
        } else {
            $clean_input = trim(strip_tags(add_slashes($data)));
        }
        return $clean_input;
    }

    private function _getRequestStatusDescr($code) {
        return ($this->_requestStatus[$code]) ? $this->_requestStatus[$code] : $this->_requestStatus[500];
    }

    private function _performRequest() {
        switch ($this->method) {
            case 'DELETE':
                $payload = $this->_cleanInputs($_GET);
                $this->callMethod($payload);
                break;
            case 'POST':
                $payload = $this->_cleanInputs($_POST);
                $this->callMethod($payload);
                //call user method?
                break;
            case 'GET':
                $payload = $this->_cleanInputs($_GET);
                $this->callMethod($payload);
                break;
            case 'PUT':
                $payload = $this->_cleanInputs($_GET);
                //$this->file = file_get_contents("php://input");
                $this->callMethod($payload);
                break;
            default:
                $this->_generateResponse('Invalid Method', ResponseCode::METHOD_NOT_ALLOWED);
                break;
        }
    }
    
    private function _getHTTPMethod()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $this->method = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $this->method = 'PUT';
            } else {
                throw new Exception("Unexpected Header");
            }
        }
    }
}

class ResponseCode {
    CONST OK = 200;
    CONST NOT_FOUND = 404;
    CONST METHOD_NOT_ALLOWED=405;
    CONST INT_SERVER_ERROR=500;
}
