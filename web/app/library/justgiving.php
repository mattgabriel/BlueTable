<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of justgiving
 *
 * @author fdevbt
 */
class justgiving {
    private $_appid = 'cb085245';
    
    public function check()
    {
        //Check if user has a justgiving account
        //https://api.justgiving.com/{appId}/v1/account/{email}
        
        //
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
    
    public function listCharities(){
        //Get user inerests https://api.justgiving.com/{appId}/v1/account/interest
        //https://api.justgiving.com/{appId}/v1/onesearch
        /*q (String)
        Your search term or terms
        g (Boolean)
        Allows you to group search results by index
        i (String)
        Narrow search results by index: Charity, Event, Fundraiser, Globalproject, LocalProject
        limit (Integer (32bit))
        Maximum number of search results to return
        offset (Integer (32bit))
        The result paging offset
        country (String)
        Two letter ISO country code for localised results*/
    }
    
    public function postDonation(){
        //Check if paypal was successful
        //If so -- donation was posted
        //otherwise donation failed
    }
}
