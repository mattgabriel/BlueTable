//
//  BlurImage.h
//  dropsarV1
//
//  Created by Matt Gabriel on 25/09/2014.
//  Copyright (c) 2014 Matt Gabriel. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface BlurImage : UIView

- (UIImage *)blurWithCoreImage:(UIImage *)sourceImage withRadius:(id)radius;

@end
