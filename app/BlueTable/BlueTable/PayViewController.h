//
//  PayViewController.h
//  BlueTable
//
//  Created by Matt Gabriel on 12/10/2014.
//  Copyright (c) 2014 Matt Gabriel. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface PayViewController : UIViewController <NSURLConnectionDelegate> {
    NSMutableData *responseData;
}

@property (strong, nonatomic) NSString *UserId;
@property (strong, nonatomic) NSString *tableId;
@property (strong, nonatomic) NSString *orderId;

@property (weak, nonatomic) IBOutlet UILabel *billPriceLabel;

@property (weak, nonatomic) IBOutlet UILabel *tipLabel;
@property (weak, nonatomic) IBOutlet UILabel *donateLabel;
@property (weak, nonatomic) IBOutlet UIButton *payButtonLabel;
- (IBAction)payNowbutton:(id)sender;

@end
