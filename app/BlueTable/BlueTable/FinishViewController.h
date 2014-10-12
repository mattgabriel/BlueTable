//
//  FinishViewController.h
//  BlueTable
//
//  Created by Matt Gabriel on 12/10/2014.
//  Copyright (c) 2014 Matt Gabriel. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface FinishViewController : UIViewController <NSURLConnectionDelegate> {
    NSMutableData *responseData;
}

@property (strong, nonatomic) NSString *orderId;
@property (strong, nonatomic) NSString *UserId;


@end
