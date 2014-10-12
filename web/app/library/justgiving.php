<?php

require_once 'restRequest.php';

class justgiving {
    private $_endpoint = 'https://api-sandbox.justgiving.com'; //-sandbox
    private $_appid = 'cb085245';
    private $_request = '';
    
    function __construct() {
        $this->_request = new restRequest();
    }
    
    public function check($email)
    {
        $service = '/'.$this->_appid .'/v1/account/'.urlencode($email);
        $header = array();
        $header[] = 'Content-type: application/json';
        $response = $this->_request->generateRequest($this->_endpoint.$service, 'GET', $header);
    }
    
    public function create()
    {
        //Create the user justgiving account
        //https://api.justgiving.com/{appId}/v1/account
        //{
        //"acceptTermsAndConditions": true,
        //"address": {
        //"country": "United Kingdom",
        //"countyOrState": "London",
        //"line1": "Second Floor, Blue Fin Building",
        //"line2": "110 Southwark Street",
        //"postcodeOrZipcode": "SE1 0TA",
        //"townOrCity": "London"
        //},
        //"causeId": null,
        //"email": "myAwesomeEmail@somewhere.com",
        //"firstName": "John",
        //"lastName": "Smith",
        //"password": "S3cr3tP4ssw0rd",
        //"reference": "Your Reference",
        //"title": "Mr"
    }
    
    public function getDonationAmountToDate()
    {
        //https://api.justgiving.com/{appId}/v1/account/donations?pageNum={value}&pageSize{value}
        //pageSize
        /*"donations": [
        {
            "amount": "2.00",
            "currencyCode": "GBP",
            "donationDate": "\/Date(1413051091199+0100)\/",
            "donationRef": "9876",
            "donorDisplayName": "Peter Queue",
            "donorLocalAmount": "2.75",
            "donorLocalCurrencyCode": "EUR",
            "estimatedTaxReclaim": 0.56,
            "id": 1234,
            "source": "SponsorshipDonations",
            "status": "Accepted",
            "thirdPartyReference": "1234-my-sdi-ref",
            "charityId": 50,
            "charityName": "The Demo Charity",
            "paymentType": "Card"
            }
        ],
        "pagination": {
            "pageNumber": 1,
            "pageSizeRequested": 50,
            "pageSizeReturned": 1,
            "totalPages": 1,
            "totalResults": 0
        }*/
    }
    
    private function getInterests(){
        $service = '/'.$this->_appid.'/v1/account/interest';
        $header = array();
        $header[] = 'Content-type: application/json';
        $header[] = 'Authorization:Basic ' . base64_encode('thai1@ualberta.ca:Blu3Tabl3');
        $response = $this->_request->generateRequest($this->_endpoint.$service, 'GET', $header);
        
        return $response;
    }
    
    public function listCharities(){
        //$interests = $this->getInterests();
        $interests = array();
        $service = '/'.$this->_appid.'/v1/charity/search';
        $options = '?pageSize=1&q='.implode(',',$interests);
        $headers = array();
        $headers[] = 'Content-type: application/json';
        return $this->_request->generateRequest($this->_endpoint.$service.$options, 'GET',$headers);
    }
    
    public function donate($cost){
        return 'Successfully donated £' . $cost;
        //Check if paypal was successful
        //If so -- donation was posted
        //otherwise donation failed
    }
}
