//
//  PayViewController.m
//  BlueTable
//
//  Created by Matt Gabriel on 12/10/2014.
//  Copyright (c) 2014 Matt Gabriel. All rights reserved.
//

#import "PayViewController.h"
#import "FinishViewController.h"

@implementation PayViewController

/*
// Only override drawRect: if you perform custom drawing.
// An empty implementation adversely affects performance during animation.
- (void)drawRect:(CGRect)rect {
    // Drawing code
}
*/

- (void) viewDidLoad {
    [super viewDidLoad];
    
    [self requestGET];
}


- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    FinishViewController *transferViewController = segue.destinationViewController;
    transferViewController.orderId = _orderId;
}


- (void)requestGET {
   
        // Create the request.
        //NSString *requestURL = @"http://166.78.145.139/api/v1/DROPS/?lat=53.472098&long=-2.300300&r=1000&alt=0&accu=600.2&fromDate=1&toDate=1&order=ASC";
        NSString *requestURL = [NSString stringWithFormat:@"http://104.130.141.81/api/order/Priceperorder/?OrderId=%@",_orderId];
        NSLog(@"Query: %@",requestURL);
        NSURLRequest *theRequest=[NSURLRequest requestWithURL:[NSURL URLWithString:requestURL]
                                                  cachePolicy:NSURLRequestUseProtocolCachePolicy
                                              timeoutInterval:60.0];
        // Create the NSMutableData to hold the received data.
        // receivedData is an instance variable declared elsewhere.
        responseData = [NSMutableData dataWithCapacity: 0];
        
        // create the connection with the request
        // and start loading the data
        NSURLConnection *theConnection=[[NSURLConnection alloc] initWithRequest:theRequest delegate:self];
        if (!theConnection) {
            // Release the receivedData object.
            responseData = nil;
            
            // Inform the user that the connection failed.
        }
}

#pragma mark NSURLConnection Delegate Methods
//Info: https://developer.apple.com/library/mac/documentation/Cocoa/Conceptual/URLLoadingSystem/Tasks/UsingNSURLConnection.html


#pragma mark NSURLConnection Delegate Methods
//Info: https://developer.apple.com/library/mac/documentation/Cocoa/Conceptual/URLLoadingSystem/Tasks/UsingNSURLConnection.html

- (void)connection:(NSURLConnection *)connection didReceiveResponse:(NSURLResponse *)response {
    // A response has been received, this is where we initialize the instance var you created
    // so that we can append data to it in the didReceiveData method
    // Furthermore, this method is called each time there is a redirect so reinitializing it
    // also serves to clear it
    //_responseData = [[NSMutableData alloc] init];
    [responseData setLength:0];
}

- (void)connection:(NSURLConnection *)connection didReceiveData:(NSData *)data {
    // Append the new data to the instance variable you declared
    [responseData appendData:data];
}

- (NSCachedURLResponse *)connection:(NSURLConnection *)connection
                  willCacheResponse:(NSCachedURLResponse*)cachedResponse {
    // Return nil to indicate not necessary to store a cached response for this connection
    return nil;
}


- (void)connectionDidFinishLoading:(NSURLConnection *)connection {
    // The request is complete and data has been received
    // You can parse the stuff in your instance variable now
    //NSString *string = [[NSString alloc] initWithData:responseData encoding:NSASCIIStringEncoding];
    //NSLog(@"%@",string);
    
    if(responseData){
        
        NSString* price;
        price = [[NSString alloc] initWithData:responseData encoding:NSASCIIStringEncoding];
        NSString *newPrice = price;
        _billPriceLabel.text = [NSString stringWithFormat:@"$%@",newPrice];
        
        _tipLabel.text = [NSString stringWithFormat:@"$%d",([newPrice intValue] * 15 / 100)];
        
        _donateLabel.text = [NSString stringWithFormat:@"$%d",([newPrice intValue] * 10 / 100)];
        
        [_payButtonLabel setTitle:
         [NSString stringWithFormat:@"1-Click PAY $%d",
            ([newPrice intValue]) +
            ([newPrice intValue] * 15 / 100) +
            ([newPrice intValue] * 10 / 100)]  forState:UIControlStateNormal];
        
        
        responseData = nil;
        
        
    }
    
}


- (void)connection:(NSURLConnection *)connection didFailWithError:(NSError *)error {
    // The request has failed for some reason!
    // Check the error var
}




- (IBAction)payNowbutton:(id)sender {
    [self requestPOST:[NSString stringWithFormat:@"TableId=%@&UserId=%@",
                       _tableId,
                       _UserId] url:@"table"];
}


- (void)requestPOST:(NSString *)query url:(NSString *)url {
    // In body data for the 'application/x-www-form-urlencoded' content type,
    // form fields are separated by an ampersand. Note the absence of a
    // leading ampersand.
    NSString *bodyData = query; //@"name=Jane+Doe&address=123+Main+St";
    
    NSMutableURLRequest *postRequest = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:[NSString stringWithFormat:@"http://104.130.141.81/api/%@",url]]];
    
    // Create the NSMutableData to hold the received data.
    // receivedData is an instance variable declared elsewhere.
    responseData = [NSMutableData dataWithCapacity: 0];
    
    // Set the request's content type to application/x-www-form-urlencoded
    [postRequest setValue:@"application/x-www-form-urlencoded" forHTTPHeaderField:@"Content-Type"];
    
    // Designate the request a POST request and specify its body data
    [postRequest setHTTPMethod:@"POST"];
    [postRequest setHTTPBody:[NSData dataWithBytes:[bodyData UTF8String] length:strlen([bodyData UTF8String])]];
    
    // Initialize the NSURLConnection and proceed as described in
    // Retrieving the Contents of a URL
    NSURLConnection *theConnection=[[NSURLConnection alloc] initWithRequest:postRequest delegate:self];
    NSLog(@"%@",theConnection);
    if (!theConnection) {
        // Release the receivedData object.
        responseData = nil;
        
        // Inform the user that the connection failed.
    } else {
        
    }
}
@end
