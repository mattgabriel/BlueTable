//
//  MenuViewController.m
//  BlueTable
//
//  Created by Matt Gabriel on 11/10/2014.
//  Copyright (c) 2014 Matt Gabriel. All rights reserved.
//

#import "MenuViewController.h"
#import "DropsTableViewCell.h"

//loads images in a separate thread
#import "UIImageView+WebCache.h"

//blur + image effects
#import "BlurImage.h"

@implementation MenuViewController {
    NSMutableArray *MenuItemId;
    NSMutableArray *MenuItemName;
    NSMutableArray *MenuItemDescription;
    NSMutableArray *MenuItemImage;
    NSMutableArray *MenuItemPrice;
}


#pragma mark - View Lifecycle

- (IBAction)someAction{
    // Your code here...
}


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
    
    MenuItemId = [NSMutableArray arrayWithObjects:@"1111", @"2222", @"3333", @"4444", @"5555", @"6666", nil];
    MenuItemName = [NSMutableArray arrayWithObjects:@"Traditional Whitebait", @"Oven-Baked Mushrooms", @"Smoked Trout", @"Light & Crispy Calamari", @"Vegetable Risotto", @"Classic Greek Salad", nil];
    MenuItemDescription = [NSMutableArray arrayWithObjects:@"With Fresh Tartare Sauce", @"With Feta Cheese & Fresh-Cut Herbs", @"Served with Toast & Horseradish", @"Served on a bed of Mixed salad with a coriander", @"With Fresh Parmesan Cheese", @"Just a salad actually", nil];
    MenuItemImage = [NSMutableArray arrayWithObjects:
                     @"http://www.jonevans.net/data/photos/18_1trendz_006726.jpg",
                     @"http://www.washingtonlife.com/wordpress/wp-content/uploads/2011/09/The-Dish-Food-Fit-For-A-Sultan-091711.jpg",
                     @"http://www.featurepics.com/FI/Thumb300/20061103/Indian-Food-Series-Okra-Dish-128391.jpg",
                     @"http://www.womansday.com/cm/womansday/images/vT/01-Chicken-Pizza-Masala-1.jpg",
                     @"http://img.foodnetwork.com/FOOD/2010/08/30/FNM_100110-Weeknight-Dinners-044_s4x3_lg.jpg",
                     @"http://www.colourbox.com/preview/5367505-853120-dish-cat-dog-food-closeup.jpg",
                     nil];
    MenuItemPrice = [NSMutableArray arrayWithObjects:@"5.75", @"8.55", @"12.99", @"9.05", @"5.55", @"8.50", nil];
    
    
    [self.myTableView reloadData];
}

- (void) viewDidAppear:(BOOL)animated {
    self.myTableView.rowHeight = 79.f;
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}



- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    static NSString *MyIdentifier = @"menuCell";
    
    DropsTableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:MyIdentifier];
    if (cell == nil){
        cell = [[DropsTableViewCell alloc] initWithStyle:UITableViewCellStyleValue2 reuseIdentifier:MyIdentifier];
    }

   
    //avoids duplicating cell contents
    if([cell viewWithTag:1]!=nil){
        [[cell viewWithTag:1] removeFromSuperview];
        [[cell viewWithTag:2] removeFromSuperview];
        [[cell viewWithTag:3] removeFromSuperview];
        [[cell viewWithTag:4] removeFromSuperview];
        [[cell viewWithTag:5] removeFromSuperview];
        [[cell viewWithTag:6] removeFromSuperview];
        [[cell viewWithTag:7] removeFromSuperview];
        [[cell viewWithTag:8] removeFromSuperview];
        [[cell viewWithTag:9] removeFromSuperview];
        [[cell viewWithTag:10] removeFromSuperview];
    }

    
    //NSURL *dropImage = [NSURL URLWithString:[NSString stringWithFormat:@"http://166.78.145.139/images/drops/%@",[_dropsList [indexPath.row] objectForKey:@"Image"]]];
    UIImageView *dropImageView = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"launchImage.png"]];
    dropImageView.tag = 7;
    dropImageView.contentMode = UIViewContentModeScaleAspectFill;
    dropImageView.layer.masksToBounds = YES;
    [dropImageView setFrame:CGRectMake(0,0,self.view.bounds.size.width ,199)];
    [cell addSubview:dropImageView];
    
    NSURL *bgImage = [NSURL URLWithString: MenuItemImage [indexPath.row]];
    //adds the main image and also the blurred image
    
    [dropImageView sd_setImageWithURL:bgImage
                     placeholderImage:[UIImage imageNamed:@"placeholder.jpg"]
                           extraImage:nil];
    
    UIView *cover = [[UIView alloc] initWithFrame:CGRectMake(0, 0, self.view.bounds.size.width, 90)];
    cover.tag = 5; //Important for finding this label
    cover.backgroundColor = [UIColor colorWithWhite:0 alpha:0.7];
    [cell addSubview:cover];
    
    
    //price
    UIView *priceView = [[UIView alloc] initWithFrame:CGRectMake(self.view.bounds.size.width - 90, 120, 90, 50)];
    priceView.tag = 4;
    priceView.backgroundColor = [UIColor colorWithRed:(57.0/255.0) green:(181.0/255.0) blue:(73.0/255.0) alpha:0.8];
    [cell addSubview:priceView];
    UILabel *priceLabel = [[UILabel alloc] initWithFrame:CGRectMake(10, 10, 100, 28)];
    priceLabel.text = [NSString stringWithFormat:@"£%@", MenuItemPrice [indexPath.row]];
    priceLabel.textColor = [UIColor whiteColor];
    priceLabel.font = [UIFont fontWithName:@"HelveticaNeue-Medium" size:22];
    [priceView addSubview:priceLabel];
    
    
    UITextView *textView1 = [[UITextView alloc] initWithFrame:CGRectMake(18, 18, self.view.bounds.size.width - 40, 0)];
    textView1.tag = 2; //Important for finding this label
    [textView1 setScrollEnabled:NO];
    textView1.editable = NO;
    textView1.backgroundColor = [UIColor clearColor];
    textView1.textContainer.lineFragmentPadding = 0; //remove padding (required)
    textView1.textContainerInset = UIEdgeInsetsZero; //remove padding (required)
    textView1.textColor = [UIColor colorWithWhite:0.0 alpha:0.8];
    
    textView1.text = MenuItemName [indexPath.row];
    textView1.font = [UIFont fontWithName:@"HelveticaNeue-Medium" size:28];
    [textView1 sizeToFit];
    [cell addSubview:textView1];
    
    
    UITextView *textView = [[UITextView alloc] initWithFrame:CGRectMake(17, 17, self.view.bounds.size.width - 40, 0)];
    textView.tag = 1; //Important for finding this label
    [textView setScrollEnabled:NO];
    textView.editable = NO;
    textView.backgroundColor = [UIColor clearColor];
    textView.textContainer.lineFragmentPadding = 0; //remove padding (required)
    textView.textContainerInset = UIEdgeInsetsZero; //remove padding (required)
    textView.textColor = [UIColor colorWithWhite:255.0 alpha:1.0];
    
    textView.text = MenuItemName [indexPath.row];
    textView.font = [UIFont fontWithName:@"HelveticaNeue-Medium" size:28];
    [textView sizeToFit];
    [cell addSubview:textView];
    
    
    UITextView *detail = [[UITextView alloc] initWithFrame:CGRectMake(17, 50, self.view.bounds.size.width - 40, 0)];
    detail.tag = 2; //Important for finding this label
    [detail setScrollEnabled:NO];
    detail.editable = NO;
    detail.backgroundColor = [UIColor clearColor];
    detail.textContainer.lineFragmentPadding = 0; //remove padding (required)
    detail.textContainerInset = UIEdgeInsetsZero; //remove padding (required)
    detail.textColor = [UIColor colorWithWhite:255.0 alpha:1.0];
    
    detail.text = @"This is the description of the dish";
    detail.font = [UIFont fontWithName:@"HelveticaNeue-Light" size:18];
    [detail sizeToFit];
    [cell addSubview:detail];
    
    
    return cell;
}


- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    return 200;
}

/****
 **** Requires the number of rows the tableView is going to display.
 ****/
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section{
    return 6; //[menuData count];
}


- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    NSLog(@"Tapped on cell %ld", (long)indexPath.row);
    
    [self requestPOST:[NSString stringWithFormat:@"MenuItemId=%@&OrderId=%@",
                       MenuItemId [indexPath.row],
                       _orderId] url:@"order/Menuiteminorder"];
    
    
    //UIView *selectedItem = [[UIView alloc] initWithFrame:CGRectMake(0, 0, self.view.bounds.size.width, self.view.bounds.size.height)];
    [_selectedItemOverlay setFrame:CGRectMake(0, 0, self.view.bounds.size.width, self.view.bounds.size.height)];
    _selectedItemOverlay.backgroundColor = [UIColor whiteColor];
    _selectedItemOverlay.userInteractionEnabled = YES;
    UITapGestureRecognizer *tapped = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(tapOnOrderDish:)];
    tapped.numberOfTapsRequired = 1;
    [_selectedItemOverlay addGestureRecognizer:tapped];
    _selectedItemOverlay.layer.zPosition = 1000;
    [self.view addSubview:_selectedItemOverlay];
    [self.view bringSubviewToFront:_selectedItemOverlay];
    
}

- (void)tapOnOrderDish:(id) sender {
    [_selectedItemOverlay removeFromSuperview];
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
    NSString *string = [[NSString alloc] initWithData:responseData
                                             encoding:NSASCIIStringEncoding];
    NSLog(@"Succeeded! %@",string);
    //clear the contents of the text box
    //return string;
    
    // Release the connection and the data object
    // by setting the properties (declared elsewhere)
    // to nil.  Note that a real-world app usually
    // requires the delegate to manage more than one
    // connection at a time, so these lines would
    // typically be replaced by code to iterate through
    // whatever data structures you are using.
    //theConnection = nil;
    responseData = nil;
    
}

- (void)connection:(NSURLConnection *)connection didFailWithError:(NSError *)error {
    // The request has failed for some reason!
    // Check the error var
}








@end