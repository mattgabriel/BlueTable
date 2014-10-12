//
//  SuccessViewController.m
//  BlueTable
//
//  Created by Matt Gabriel on 12/10/2014.
//  Copyright (c) 2014 Matt Gabriel. All rights reserved.
//

#import "SuccessViewController.h"
#import "PayViewController.h"

@interface SuccessViewController ()

@end

@implementation SuccessViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
    
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}


- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    PayViewController *transferViewController = segue.destinationViewController;
    transferViewController.UserId = _UserId;
    transferViewController.orderId = _orderId;
    transferViewController.tableId = _tableId;
}


/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

@end
