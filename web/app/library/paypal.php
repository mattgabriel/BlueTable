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
        $this->pay();
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
        $headers[] = "Content-type: application/json";
        $headers[] = "Authorization: Bearer ".$this->_token;
        
        $address = new stdClass();
        $address->line1 = 'test';
        $address->city = 'london';
        $address->postal_code = 'NW9 5ZW';
        $address->country_code = 'UK';
        
        $credit_card = new stdClass();
        $credit_card->number = '4137356119963118';
        $credit_card->type = 'visa';
        $credit_card->expire_month = '10';
        $credit_card->expire_year = '2019';
        $credit_card->cvv2 = '999';
        $credit_card->first_name = 'Test';
        $credit_card->last_name = 'Test';
        $credit_card->billing_address = $address;
        
        $fi = new stdClass();
        $fi->credit_card = $credit_card;
        
        $payer = new stdClass();
        $payer->payment_method = 'credit_card';
        $payer->funding_instruments = array($fi);
        
        $bill_details = new stdClass();
        $bill_details->tax = '0.03';
        $bill_details->shipping = '0';
        $bill_details->subtotal = '7.41';
        
        $bill = new stdClass();
        $bill->total = '7.47';
        $bill->currency = 'GBP';
        $bill->details = $bill_details;
        
        $transaction = new stdClass();
        $transaction->amount = $bill;
        $transaction->description = 'test';
        
        $payment = new stdClass();
        $payment->payer = $payer;
        $payment->intent = 'sale';
        $payment->transactions = array($transaction);
        
        $data = json_encode($payment);
        
        $response = $this->_request->generateRequest($this->_endpoint.$service, 'POST', $headers, $data);
        var_dump($response);
    }
    
    public function getConsent()
    {
        $service = 'https://www.sandbox.paypal.com/webapps/auth/protocol/openidconnect/v1/authorize';
        $options = '?client_id='.$this->_clientid . '&reponse_type=code&scope=profile+email+https%3A%2F%2Furi.paypal.com%2Fservices%2Fpaypalattributes&redirect_uri=http%3A%2F%2F104.130.141.81%2Ftest';
        $response = $this->_request->generateRequest($service.$options, 'GET', '');
        var_dump ($response);
    }
}
