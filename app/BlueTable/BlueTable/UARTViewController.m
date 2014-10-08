//
//  UARTViewController.m
//  Bluefruit Connect
//
//  Created by Adafruit Industries on 2/5/14.
//  Copyright (c) 2014 Adafruit Industries. All rights reserved.
//

#import "UARTViewController.h"
#import "NSString+hex.h"
#import "NSData+hex.h"

#define kKeyboardAnimationDuration 0.3f

@interface UARTViewController(){
    
    NSString    *unkownCharString;
    
}

@end

@implementation UARTViewController


- (id)initWithDelegate:(id<UARTViewControllerDelegate>)aDelegate{
    
    
    return self;
    
}





#pragma mark - View Lifecycle


- (void)viewDidLoad{
    
    [super viewDidLoad];
    
    //initialization
    
}


- (void)didReceiveMemoryWarning{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}



@end
