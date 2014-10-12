<?php

require_once 'restRequest.php';

class paypal {
    private $_endpoint = 'https://api.sandbox.paypal.com';
    private $_clientid = 'AQw3NRBEKDGbnTRaHOsmCWf_TkrmxWCtFYK8_G54wVGXBc8FH9Ixx86y6NvA';
    private $_secret = 'EOdwcxBce4qPSChE1gWjh9pddVO30JC8D2f_Ys4K47j6327p5kWMLcYoSq7O';
    private $_token = '';
    private $_request = '';
    
    function __construct() {
        $this->_request = new restRequest();
        $this->_token = $this->getToken();
        $this->getConsent();
    }
    
    public function login()
    {
        //Login to paypal
        
    }
    
    private function getToken()
    {
        $service = '/v1/oauth2/token';
        
        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Accept-Language: en_US';
        $headers[] = 'Content-type: application/x-www-form-urlencoded';
        $headers[] = 'Authorization:Basic ' . base64_encode($this->_clientid.':'.$this->_secret);
        
        $response = $this->_request->generateRequest($this->_endpoint.$service, 'POST', $headers, 'grant_type=client_credentials');
        return $response->access_token;
    }
    
    public function create()
    {
        //create paypal account
    }
    
    public function getPayments()
    {
        //get paypal payments related to restaurants
    }
    
    public function getUserInfo()
    {
        $service = '/v1/identity/openidconnect/userinfo/?schema=openid';
        
        $headers = array();
        $headers[] = 'Content-Type:application/json';
        $headers[] = 'Authorization: Bearer ' . $this->_token;
        
        $response = $this->_request->generateRequest($this->_endpoint.$service, 'POST', $headers, '');
        var_dump($response);
        //get paypal user info to enter into db
        //https://api.sandbox.paypal.com/v1/identity/openidconnect/userinfo/?schema=openid
        /*"user_id": "https://www.paypal.com/webapps/auth/server/64ghr894040044",
  "name": "Peter Pepper",
  "given_name": "Peter",
  "family_name": "Pepper",
  "email": "ppuser@example.com"*/
    }
    
    public function pay()
    {
        $service = '/v1/payments/payment';
        
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $this->_token;
        
        $data = new stdClass();
        $data->intent = 'sale';
        
        /*curl -v https://api.sandbox.paypal.com/v1/payments/payment \
-H 'Content-Type: application/json' \
-H 'Authorization: Bearer <Access-Token>' \
-d '{
  "intent":"sale",
  "redirect_urls":{
    "return_url":"http://example.com/your_redirect_url.html",
    "cancel_url":"http://example.com/your_cancel_url.html"
  },
  "payer":{
    "payment_method":"paypal"
  },
  "transactions":[
    {
      "amount":{
        "total":"7.47",
        "currency":"USD"
      }
    }
  ]
}'*/
    }
    
    public function getConsent()
    {
        $service = 'https://www.sandbox.paypal.com/webapps/auth/protocol/openidconnect/v1/authorize';
        $options = '?client_id='.$this->_clientid . '&reponse_type=code&scope=profile+email+https%3A%2F%2Furi.paypal.com%2Fservices%2Fpaypalattributes&redirect_uri=http%3A%2F%2F104.130.141.81%2Ftest';
        $response = $this->_request->generateRequest($service.$options, 'GET', '');
        var_dump ($response);
    }
}
