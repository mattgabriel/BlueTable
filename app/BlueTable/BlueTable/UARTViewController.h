//
//  UARTViewController.h
//  Bluefruit Connect
//
//  Created by Adafruit Industries on 2/5/14.
//  Copyright (c) 2014 Adafruit Industries. All rights reserved.
//

#import <UIKit/UIKit.h>

@protocol UARTViewControllerDelegate <NSObject>

- (void)sendData:(NSData*)newData;

@end


@interface UARTViewController : UIViewController

typedef enum {
    LOGGING,
    RX,
    TX,
} ConsoleDataType;

typedef enum {
    ASCII = 0,
    HEX,
} ConsoleMode;

@property (weak, nonatomic) id<UARTViewControllerDelegate>      delegate;
- (id)initWithDelegate:(id<UARTViewControllerDelegate>)aDelegate;

@end
