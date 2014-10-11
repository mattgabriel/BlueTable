//
//  ViewController.m
//  BlueTable
//
//  Created by Matt Gabriel on 04/10/2014.
//  Copyright (c) 2014 Matt Gabriel. All rights reserved.
//

#import "ViewController.h"
#import <QuartzCore/QuartzCore.h>
#import "NSString+hex.h"
#import "NSData+hex.h"

#define CONNECTING_TEXT @"Connecting…"
#define DISCONNECTING_TEXT @"Disconnecting…"
#define DISCONNECT_TEXT @"Disconnect"
#define CONNECT_TEXT @"Connect"

@interface ViewController ()<UIAlertViewDelegate>{
    
    CBCentralManager    *cm;
    UIAlertView         *currentAlertView;
    UARTPeripheral      *currentPeripheral;
    BOOL                isConnectedToTable;
    
}
            

@end

@implementation ViewController

#pragma mark - View Lifecycle

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view, typically from a nib.
    
    _UserId = @"test1234";
    
    //make status bar white
    self.navigationController.navigationBar.barStyle = UIBarStyleBlack;
    
    //initialise BT Central manager
    cm = [[CBCentralManager alloc] initWithDelegate:self queue:nil];
    
    _connectionMode = ConnectionModeNone;
    _connectionStatus = ConnectionStatusDisconnected;
    currentAlertView = nil;
    
    isConnectedToTable = false;
    
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}


- (IBAction)connectButton:(id)sender {
    if(!isConnectedToTable){
        NSLog(@"Starting UART Mode …");
        _connectionMode = ConnectionModeUART;
    
        _connectionStatus = ConnectionStatusScanning;
        
        [self scanForPeripherals];
    
        currentAlertView = [[UIAlertView alloc]initWithTitle:@"Scanning tables..."
                                                     message:nil
                                                    delegate:self
                                           cancelButtonTitle:@"Cancel"
                                           otherButtonTitles:nil];
    
        [currentAlertView show];
        [sender setTitle:@"Leave table" forState:UIControlStateNormal];
        isConnectedToTable = true;
    } else {
        [self disconnect];
        [sender setTitle:@"Connect to table to order" forState:UIControlStateNormal];
        isConnectedToTable = false;
    }

}


- (void)scanForPeripherals{
    //Look for available Bluetooth LE devices
    //skip scanning if UART is already connected
    NSArray *connectedPeripherals = [cm retrieveConnectedPeripheralsWithServices:@[UARTPeripheral.uartServiceUUID]];
    if ([connectedPeripherals count] > 0) {
        //connect to first peripheral in array
        [self connectPeripheral:[connectedPeripherals objectAtIndex:0]];
    } else {
        
        [cm scanForPeripheralsWithServices:@[UARTPeripheral.uartServiceUUID]
                                   options:@{CBCentralManagerScanOptionAllowDuplicatesKey: [NSNumber numberWithBool:NO]}];
    }
}

- (void)connectPeripheral:(CBPeripheral*)peripheral{
    //Connect Bluetooth LE device
    //Clear off any pending connections
    [cm cancelPeripheralConnection:peripheral];
    
    //Connect
    currentPeripheral = [[UARTPeripheral alloc] initWithPeripheral:peripheral delegate:self];
    [cm connectPeripheral:peripheral options:@{CBConnectPeripheralOptionNotifyOnDisconnectionKey: [NSNumber numberWithBool:YES]}];
}

- (void)disconnect{
    //Disconnect Bluetooth LE device
    _connectionStatus = ConnectionStatusDisconnected;
    _connectionMode = ConnectionModeNone;
    [cm cancelPeripheralConnection:currentPeripheral.peripheral];
}



#pragma mark UIAlertView delegate methods

- (void)alertView:(UIAlertView*)alertView clickedButtonAtIndex:(NSInteger)buttonIndex{
    //the only button in our alert views is cancel, no need to check button index
    if (_connectionStatus == ConnectionStatusConnected) {
        [self disconnect];
    } else if (_connectionStatus == ConnectionStatusScanning){
        [cm stopScan];
    }

    _connectionStatus = ConnectionStatusDisconnected;
    _connectionMode = ConnectionModeNone;
    
    currentAlertView = nil;
    
    //alert dismisses automatically @ return
    
}



#pragma mark Navigation Controller delegate methods


- (void)navigationController:(UINavigationController*)navigationController willShowViewController:(UIViewController*)viewController animated:(BOOL)animated{

    //disconnect when returning to main view
    if (_connectionStatus == ConnectionStatusConnected && [viewController isEqual:viewController]) {
        [self disconnect];
        
        //dismiss UART keyboard
        //[_uartViewController.inputField resignFirstResponder];
    }
}



#pragma mark CBCentralManagerDelegate


- (void) centralManagerDidUpdateState:(CBCentralManager*)central{
    if (central.state == CBCentralManagerStatePoweredOn){
        //respond to powered on
    } else if (central.state == CBCentralManagerStatePoweredOff){
        //respond to powered off
    }
}

- (void) centralManager:(CBCentralManager*)central didDiscoverPeripheral:(CBPeripheral*)peripheral advertisementData:(NSDictionary*)advertisementData RSSI:(NSNumber*)RSSI{
    
    NSLog(@"Did discover peripheral %@", peripheral.name);
    [cm stopScan];
    [self connectPeripheral:peripheral];
}

- (void) centralManager:(CBCentralManager*)central didConnectPeripheral:(CBPeripheral*)peripheral{
    if ([currentPeripheral.peripheral isEqual:peripheral]){
        if(peripheral.services){
            NSLog(@"Did connect to existing peripheral %@", peripheral.name);
            [currentPeripheral peripheral:peripheral didDiscoverServices:nil]; //already discovered services, DO NOT re-discover. Just pass along the peripheral.
            
        } else {
            NSLog(@"Did connect peripheral %@", peripheral.name);
            [currentPeripheral didConnect];
        }
    }
}

- (void) centralManager:(CBCentralManager*)central didDisconnectPeripheral:(CBPeripheral*)peripheral error:(NSError*)error{
    NSLog(@"Did disconnect peripheral %@", peripheral.name);
    
    //respond to disconnected
    [self peripheralDidDisconnect];
    
    if ([currentPeripheral.peripheral isEqual:peripheral]){
        [currentPeripheral didDisconnect];
    }
}


#pragma mark UARTPeripheralDelegate


- (void)didReadHardwareRevisionString:(NSString*)string{
    //Once hardware revision string is read, connection to Bluefruit is complete
    NSLog(@"HW Revision: %@", string);
    
    //Bail if we aren't in the process of connecting
    if (currentAlertView == nil){
        return;
    }
    
    _connectionStatus = ConnectionStatusConnected;
    
    
    //Dismiss Alert view & update main view
    [currentAlertView dismissWithClickedButtonIndex:-1 animated:NO];
    
    currentAlertView = nil;
    
    //send UserId to Arduino via Bluetooth
    NSString *newString = @"UserId";
    NSData *data = [NSData dataWithBytes:newString.UTF8String length:newString.length];
    [self sendData:data];
}


- (void)uartDidEncounterError:(NSString*)error{
    //Dismiss "scanning …" alert view if shown
    if (currentAlertView != nil) {
        [currentAlertView dismissWithClickedButtonIndex:0 animated:NO];
    }
    
    //Display error alert
    UIAlertView *alert = [[UIAlertView alloc]initWithTitle:@"Error"
                                                   message:error
                                                  delegate:nil
                                         cancelButtonTitle:@"OK"
                                         otherButtonTitles:nil];
    [alert show];
}


- (void)didReceiveData:(NSData*)newData{
    
    //Data incoming from UART peripheral
    
    //Debug
        //NSString *hexString = [newData hexRepresentationWithSpaces:YES];
        //NSLog(@"Received: %@", hexString);
    
    if (_connectionStatus == ConnectionStatusConnected || _connectionStatus == ConnectionStatusScanning) {
        //convert data to string & replace characters we can't display
        int dataLength = (int)newData.length;
        uint8_t data[dataLength];
        
        [newData getBytes:&data length:dataLength];
        
        for (int i = 0; i<dataLength; i++) {
            
            if ((data[i] <= 0x1f) || (data[i] >= 0x80)) {    //null characters
                if ((data[i] != 0x9) && //0x9 == TAB
                    (data[i] != 0xa) && //0xA == NL
                    (data[i] != 0xd)) { //0xD == CR
                    data[i] = 0xA9;
                }
            }
        }
        
        NSString *newString = [[NSString alloc]initWithBytes:&data
                                                      length:dataLength
                                                    encoding:NSUTF8StringEncoding];
        NSLog(@"Received: %@",newString);
        _receiveLabel.text = newString;
        
        //send user to menuViewcontroller
        //_menuViewController = [[MenuViewController alloc] init];
        //[_navController pushViewController:_menuViewController animated:YES];
        
        MenuViewController *menuViewController = [self.storyboard instantiateViewControllerWithIdentifier:@"menuViewController"];
        menuViewController.tableId = newString;
        [self.navigationController pushViewController:menuViewController animated:YES];
    }
}


- (void)peripheralDidDisconnect{
    //respond to device disconnecting
    
    //if we were in the process of scanning/connecting, dismiss alert
    if (currentAlertView != nil) {
        [self uartDidEncounterError:@"Peripheral disconnected"];
    }
    
    //if status was connected, then disconnect was unexpected by the user, show alert
    if (_connectionStatus == ConnectionStatusConnected){
        
            //display disconnect alert
            UIAlertView *alert = [[UIAlertView alloc]initWithTitle:@"Disconnected"
                                                           message:@"BLE peripheral has disconnected"
                                                          delegate:nil
                                                 cancelButtonTitle:@"OK"
                                                 otherButtonTitles: nil];
            
            [alert show];
        }
    _connectionStatus = ConnectionStatusDisconnected;
    _connectionMode = ConnectionModeNone;
}



- (void)alertBluetoothPowerOff{
    
    //Respond to system's bluetooth disabled
    
    NSString *title     = @"Bluetooth Power";
    NSString *message   = @"You must turn on Bluetooth in Settings in order to connect to a device";
    UIAlertView *alertView = [[UIAlertView alloc] initWithTitle:title message:message delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
    [alertView show];
}


- (void)alertFailedConnection{
    
    //Respond to unsuccessful connection
    
    NSString *title     = @"Unable to connect";
    NSString *message   = @"Please check power & wiring,\nthen reset your Arduino";
    UIAlertView *alertView = [[UIAlertView alloc] initWithTitle:title message:message delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
    [alertView show];
    
}


#pragma mark UartViewControllerDelegate / PinIOViewControllerDelegate


- (void)sendData:(NSData*)newData{
    
    //Output data to UART peripheral
    
    NSString *hexString = [newData hexRepresentationWithSpaces:YES];
    NSLog(@"Sending: %@", hexString);
    
    [currentPeripheral writeRawData:newData];
    
}



@end
