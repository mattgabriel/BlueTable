//
//  MenuViewController.h
//  BlueTable
//
//  Created by Matt Gabriel on 11/10/2014.
//  Copyright (c) 2014 Matt Gabriel. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface MenuViewController : UIViewController <NSURLConnectionDelegate, UITableViewDelegate, UITableViewDataSource> {
    NSMutableData *responseData;
}

@property (strong, nonatomic) NSString *UserId;
@property (strong, nonatomic) NSString *tableId;
@property (strong, nonatomic) NSString *orderId;
//@property (strong, nonatomic) NSMutableArray *menuData;

@property (strong, nonatomic) UIView *selectedItemOverlay;

@property (weak, nonatomic) IBOutlet UITableView *myTableView;

@end

