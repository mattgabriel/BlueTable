<?php

require_once 'restRequest.php';

class sendgrid {
    private $_smtp = 'smtp.sendgrid.net';
    private $_user = 'Bmanth60';
    private $_pass = 'Blu3Tabl3';
    
    private $_endpoint = 'https://api.sendgrid.com';
    private $_request = '';
    
    function __construct() {
        $this->_request = new restRequest();
    }
    
    public function send($body){
        $service = '/api/mail.send.json';
        $header = array();
        $header[] = 'Content-type: application/x-www-form-urlencoded';
        $data = 'api_user='.$this->_user
                .  '&api_key='.$this->_pass
                .  '&to[]=brian@excelwithbusiness.com'
                .  '&toname[]=Matt'
                .  '&subject=Receipt -- Thank you'
                .  '&text=' . $body
                .  '&from=noreply@bluetable.com';
        
        $response = $this->_request->generateRequest($this->_endpoint.$service, 'POST',$header,$data);
        var_dump($response);
    }
}
