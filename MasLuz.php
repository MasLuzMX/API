<?php
class MasLuz
{

    /**
     * @version 0.0.1
     */
    const VERSION  = "0.0.1";

    /**
     * @var $API_ROOT_URL is a main URL to access the API's.
     * @var $OAUTH_URL is a url to autenticate.
     */
    protected $API_ROOT_URL = "ec2-54-69-99-24.us-west-2.compute.amazonaws.com";
    protected $OAUTH_URL    = "ec2-54-69-99-24.us-west-2.compute.amazonaws.com/oauth";
    
    protected $client_id;
    protected $client_secret;
    protected $redirect_uri;
    protected $access_token;
    protected $refresh_token;


    /**
     * Configuration for CURL
     */
    public static $CURL_OPTS = array(
        CURLOPT_USERAGENT => "MasLuz-SDK-0.0.1", 
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CONNECTTIMEOUT => 10, 
        CURLOPT_RETURNTRANSFER => 1, 
        CURLOPT_TIMEOUT => 60
    );


    /**
     * Constructor method. Set all variables to connect 
     *
     * @param string $client_id
     * @param string $client_secret
     * @param string $access_token
     * @param string $refresh_token
     */
    public function __construct($client_id, $client_secret, $access_token = null, $refresh_token = null) {
        $this->client_id     = $client_id;
        $this->client_secret = $client_secret;
        $this->access_token  = $access_token;
        $this->refresh_token = $refresh_token;
    }


    /**
     * Execute a POST Request to create a new AccessToken 
     * 
     * @return string|mixed
     */
    public function getAccessToken() {

 
        if(!$this->access_token) {

            $body = array(
                'grant_type'    => 'client_credentials',
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret
            );

            $opts = array(
                CURLOPT_POST => true, 
                CURLOPT_POSTFIELDS => $body
            );

        
            $request = $this->execute($this->OAUTH_URL, $opts);

            if($request["httpCode"] == 200) {  

                $this->access_token = $request["body"]->access_token;

                return $this->access_token;

            } else {
                return $request;
            }   
        }

        return $this->access_token;      
    }

    /**
     * Execute a GET Request
     * 
     * @param string $path
     * @param array $params
     * @return mixed
     */
    public function get($path, $params = null) {
        $exec = $this->execute($path, null, $params);

        return $exec;
    }

    /**
     * Execute a POST Request
     * 
     * @param string $body
     * @param array $params
     * @return mixed
     */
    public function post($path, $body = null, $params = array()) {
        $body = json_encode($body);
        $opts = array(
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => $body
        );
        
        $exec = $this->execute($path, $opts, $params);

        return $exec;
    }

    /**
     * Execute a PUT Request
     * 
     * @param string $path
     * @param string $body
     * @param array $params
     * @return mixed
     */
    public function put($path, $body = null, $params) {
        $body = json_encode($body);
        $opts = array(
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => $body
        );
        
        $exec = $this->execute($path, $opts, $params);

        return $exec;
    }

    /**
     * Execute a DELETE Request
     * 
     * @param string $path
     * @param array $params
     * @return mixed
     */
    public function delete($path, $params) {
        $opts = array(
            CURLOPT_CUSTOMREQUEST => "DELETE"
        );
        
        $exec = $this->execute($path, $opts, $params);
        
        return $exec;
    }

    /**
     * Execute a OPTION Request
     * 
     * @param string $path
     * @param array $params
     * @return mixed
     */
    public function options($path, $params = null) {
        $opts = array(
            CURLOPT_CUSTOMREQUEST => "OPTIONS"
        );
        
        $exec = $this->execute($path, $opts, $params);

        return $exec;
    }

    /**
     * Execute all requests and returns the json body and headers
     * 
     * @param string $path
     * @param array $opts
     * @param array $params
     * @return mixed
     */
    public function execute($path, $opts = array(), $params = array()) {
        $uri = $this->make_path($path, $params);

        $ch = curl_init($uri);
        curl_setopt_array($ch, self::$CURL_OPTS);

        if($this->access_token){
            $oauth = array(
                CURLOPT_HTTPHEADER => array('Authorization: Bearer '.$this->access_token)
            );
            curl_setopt_array($ch, $oauth);
        }

        if(!empty($opts))
            curl_setopt_array($ch, $opts);

        $res=curl_exec($ch);

        
        $return["body"] = json_decode($res);
        $return["httpCode"] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        
        return $return;
    }



    /**
     * Check and construct an real URL to make request
     * 
     * @param string $path
     * @param array $params
     * @return string
     */
    private function make_path($path, $params = array()) {
        if (!preg_match("/^http/", $path)) {
            if (!preg_match("/^\//", $path)) {
                $path = '/'.$path;
            }
            $uri = $this->API_ROOT_URL.$path;
        } else {
            $uri = $this->API_ROOT_URL.$path;
        }

        if(!empty($params)) {
            $paramsJoined = array();

            foreach($params as $param => $value) {
               $paramsJoined[] = "$param=$value";
            }
            $params = '?'.implode('&', $paramsJoined);
            $uri = $uri.$params;
        }

        return $uri;
    }

}
