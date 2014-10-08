//
//  ViewController.h
//  BlueTable
//
//  Created by Matt Gabriel on 04/10/2014.
//  Copyright (c) 2014 Matt Gabriel. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <CoreBluetooth/CoreBluetooth.h>
#import "UARTPeripheral.h"
#import "UARTViewController.h"

@interface ViewController : UIViewController <UINavigationControllerDelegate, CBCentralManagerDelegate, UARTPeripheralDelegate>

typedef enum {
    ConnectionModeNone  = 0,
    ConnectionModeUART,
} ConnectionMode;

typedef enum {
    ConnectionStatusDisconnected = 0,
    ConnectionStatusScanning,
    ConnectionStatusConnected,
} ConnectionStatus;


@property (nonatomic, assign) ConnectionMode                    connectionMode;
@property (nonatomic, assign) ConnectionStatus                  connectionStatus;

- (IBAction)connectButton:(id)sender;

@end

