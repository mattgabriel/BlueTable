//
//  MenuViewController.m
//  BlueTable
//
//  Created by Matt Gabriel on 11/10/2014.
//  Copyright (c) 2014 Matt Gabriel. All rights reserved.
//

#import "MenuViewController.h"

@implementation MenuViewController


#pragma mark - View Lifecycle

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view, typically from a nib.
    
    _UserId = @"test1234";
    
    //make status bar white
    self.navigationController.navigationBar.barStyle = UIBarStyleBlack;
    [self.navigationController.navigationBar setTintColor:[UIColor whiteColor]];
    self.navigationController.navigationBar.topItem.title = @"Back";
    UINavigationController *navCon  = (UINavigationController*) [self.navigationController.viewControllers objectAtIndex:1];
    navCon.navigationItem.title = @"Today's menu";
    
    NSLog(@"TableId = %@", _tableId);
    
    /*NSMutableDictionary *menuItem = [[NSMutableDictionary alloc] init];
    [menuItem setObject:@"1234" forKey:@"MenuItem"];
    [menuItem setObject:@"Pizza" forKey:@"MenuItemName"];
    [menuItem setObject:@"This is a pizza" forKey:@"MenuItemDescription"];
    [menuItem setObject:@"" forKey:@"MenuItemImage"];
    [menuItem setObject:@"£12.99" forKey:@"MenuItemPrice"];
    [menuItem setObject:@"" forKey:@"MenuItemProtein"];
    [menuItem setObject:@"" forKey:@"MenuItemCarbs"];
    [menuItem setObject:@"" forKey:@"MenuItemIsSpicy"];
    [menuItem setObject:@"" forKey:@"MenuItemIsVegetarian"];
    [menuItem setObject:@"" forKey:@"MenuItemIsVegan"];*/
    
    [self requestMenu];
    
    [self.myTableView reloadData];
    
    
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}




- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    static NSString *MyIdentifier = @"menuCell";
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:MyIdentifier];
    if (cell == nil) {
        cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault  reuseIdentifier:MyIdentifier];
    }

    
    cell.textLabel.text = [_menuData [indexPath.row] objectForKey:@"MenuItemName"];    
    
    return cell;
}

/****
 **** Requires the number of rows the tableView is going to display.
 ****/
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section{
    return [_menuData count];
}





- (void)requestMenu {
    
        NSString *requestURL = [NSString stringWithFormat:@"http://104.130.141.81/api/menu?restaurantid=asd123fx"];
        
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
        NSError* error;
        NSDictionary* json = [NSJSONSerialization
                              JSONObjectWithData:responseData
                              
                              options:kNilOptions
                              error:&error];
        //NSLog(@"%@",json);
        //clear all subviews (drops) in scrollView
        //[_scrollView.subviews makeObjectsPerformSelector: @selector(removeFromSuperview)];
        //NSLog(@"removed views");
        //NSLog(@"Loading views");
        ////////////////////////////////////NSLog(@"%@",json);
        //loop through all the drops
        //and display them on the scrollView
        int counter = 0;
        [_menuData removeAllObjects];
        for (NSDictionary *groupDic in json) {
          
            //NSLog(@"%@",json);
            
            NSMutableDictionary *menuItem = [[NSMutableDictionary alloc] init];
            [menuItem setObject:[groupDic objectForKey:@"MenuItem"] forKey:@"MenuItem"];
            [menuItem setObject:[groupDic objectForKey:@"MenuItemName"] forKey:@"MenuItemName"];
            [menuItem setObject:[groupDic objectForKey:@"MenuItemDescription"] forKey:@"MenuItemDescription"];
            [menuItem setObject:[groupDic objectForKey:@"MenuItemImage"] forKey:@"MenuItemImage"];
            [menuItem setObject:[groupDic objectForKey:@"MenuItemPrice"] forKey:@"MenuItemPrice"];
            [menuItem setObject:[groupDic objectForKey:@"MenuItemProtein"] forKey:@"MenuItemProtein"];
            [menuItem setObject:[groupDic objectForKey:@"MenuItemCarbs"] forKey:@"MenuItemCarbs"];
            [menuItem setObject:[groupDic objectForKey:@"MenuItemIsSpicy"] forKey:@"MenuItemIsSpicy"];
            [menuItem setObject:[groupDic objectForKey:@"MenuItemIsVegetarian"] forKey:@"MenuItemIsVegetarian"];
            [menuItem setObject:[groupDic objectForKey:@"MenuItemIsVegan"] forKey:@"MenuItemIsVegan"];
            
            [_menuData addObject:menuItem];
            counter++;
        }
        
        NSLog(@"%@",_menuData);
        
        
        [self.myTableView reloadData];
        
        // Release the connection and the data object
        // by setting the properties (declared elsewhere)
        // to nil.  Note that a real-world app usually
        // requires the delegate to manage more than one
        // connection at a time, so these lines would
        // typically be replaced by code to iterate through
        // whatever data structures you are using.
        //theConnection = nil;
        responseData = nil;
        
        //remove preloader
    }
    
}

- (void)connection:(NSURLConnection *)connection didFailWithError:(NSError *)error {
    // The request has failed for some reason!
    // Check the error var
}




@end