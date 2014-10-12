//
//  FinishViewController.m
//  BlueTable
//
//  Created by Matt Gabriel on 12/10/2014.
//  Copyright (c) 2014 Matt Gabriel. All rights reserved.
//

#import "FinishViewController.h"

@interface FinishViewController ()

@end

@implementation FinishViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    
    _orderId = @"6808";
    // Do any additional setup after loading the view.
    
    [self requestPOST:[NSString stringWithFormat:@"&OrderId=%@",
                       _orderId] url:@"Concludeorder"];
    
    
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
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
    [postRequest setHTTPMethod:@"PUT"];
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
        responseData = nil;
      }
    
}


- (void)connection:(NSURLConnection *)connection didFailWithError:(NSError *)error {
    // The request has failed for some reason!
    // Check the error var
}




@end
