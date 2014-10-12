//
//  DropsTableViewCell.m
//  dropsarV1
//
//  Created by Matt Gabriel on 11/06/2014.
//  Copyright (c) 2014 Matt Gabriel. All rights reserved.
//

#import "DropsTableViewCell.h"

@implementation DropsTableViewCell

- (id)initWithStyle:(UITableViewCellStyle)style reuseIdentifier:(NSString *)reuseIdentifier
{
    self = [super initWithStyle:style reuseIdentifier:reuseIdentifier];
    if (self) {
        // Initialization code
    }
    return self;
}

- (void)awakeFromNib
{
    // Initialization code
}

- (void)setSelected:(BOOL)selected animated:(BOOL)animated
{
    [super setSelected:selected animated:animated];

    // Configure the view for the selected state
}

@end
