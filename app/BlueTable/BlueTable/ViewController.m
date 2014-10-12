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
    NSString *randomOrderId = [NSString stringWithFormat:@"%i",rand()%10000+1];
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
        
        
        
        [self requestPOST:[NSString stringWithFormat:@"UserId=%@&OrderId=%@&TableId=%@",
                           _UserId,
                           randomOrderId,
                           _receiveLabel.text] url:@"order"];
        
        
        //send user to menuViewcontroller
        MenuViewController *menuViewController = [self.storyboard instantiateViewControllerWithIdentifier:@"menuViewController"];
        menuViewController.tableId = newString;
        menuViewController.orderId = randomOrderId;
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







/*
 #pragma mark - Navigation
 
 // In a storyboard-based application, you will often want to do a little preparation before navigation
 - (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender
 {
 // Get the new view controller using [segue destinationViewController].
 // Pass the selected object to the new view controller.
 }
 */

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
