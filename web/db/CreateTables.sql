--
-- Database: `BlueTable`
--

-- --------------------------------------------------------

--
-- Table structure for table `Restaurants`
--

CREATE TABLE IF NOT EXISTS `Restaurants` (
    `AutoId` int(11) NOT NULL AUTO_INCREMENT,
    `RestaurantId` varchar(50) NOT NULL,
    `ManagerUserId` varchar(50) NOT NULL,
    `Name` varchar(50) NOT NULL,
    `Longitude` Decimal(9,6) DEFAULT NULL,
    `Latitude` Decimal(9,6) DEFAULT NULL,
    `DateCreated` datetime NOT NULL,
    PRIMARY KEY (`AutoId`),
    UNIQUE (`RestaurantId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
ALTER TABLE `Restaurants` ADD INDEX (RestaurantId);


-- --------------------------------------------------------

--
-- Table structure for table `Restaurants`
--

CREATE TABLE IF NOT EXISTS `Tables` (
    `AutoId` int(11) NOT NULL AUTO_INCREMENT,
    `RestaurantId` varchar(50) NOT NULL,
    `TableId` varchar(50) NOT NULL,
    `TableNumber` int(3) NOT NULL,
    PRIMARY KEY (`AutoId`),
    UNIQUE (`TableId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
ALTER TABLE `Table` ADD INDEX (TableId);


-- --------------------------------------------------------

--
-- Table structure for table `Order`
--

CREATE TABLE IF NOT EXISTS `Order` (
    `AutoId` int(11) NOT NULL AUTO_INCREMENT,
    `RestaurantId` varchar(50) NOT NULL,
    `OrderId` varchar(50) NOT NULL,
    `UserId` varchar(50) NOT NULL,
    `TableId` varchar(50) NOT NULL,
    `MenuItemsIds` varchar(1000) NOT NULL,
    `TotalPrice` DECIMAL(10, 2) NOT NULL,
    `Date` datetime NOT NULL,
    PRIMARY KEY (`AutoId`),
    UNIQUE (`OrderId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
ALTER TABLE `Order` ADD INDEX (OrderId);


-- --------------------------------------------------------

--
-- Table structure for table `Order`
--

CREATE TABLE IF NOT EXISTS `Bill` (
    `AutoId` int(11) NOT NULL AUTO_INCREMENT,
    `BillId` varchar(50) NOT NULL,
    `RestaurantId` varchar(50) NOT NULL,
    `UserId` varchar(50) NOT NULL,
    `OrderId` varchar(50) NOT NULL,
    `Status` int(1) NOT NULL DEFAULT 0,
    `Price` DECIMAL(10, 2) NOT NULL,
    `NeedToPrintReceipt` TINYINT(1) NOT NULL,
    `ReceiptWasPrinted` TINYINT(1) NOT NULL,
    `Date` datetime NOT NULL,
    PRIMARY KEY (`AutoId`),
    UNIQUE (`BillId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
ALTER TABLE `Bill` ADD INDEX (BillId);


-- --------------------------------------------------------

--
-- Table structure for table `Order`
--

CREATE TABLE IF NOT EXISTS `Bill` (
    `AutoId` int(11) NOT NULL AUTO_INCREMENT,
    `BillId` varchar(50) NOT NULL,
    `RestaurantId` varchar(50) NOT NULL,
    `UserId` varchar(50) NOT NULL,
    `OrderId` varchar(50) NOT NULL,
    `Status` int(1) NOT NULL DEFAULT 0,
    `Price` DECIMAL(10, 2) NOT NULL,
    `NeedToPrintReceipt` TINYINT(1) NOT NULL,
    `ReceiptWasPrinted` TINYINT(1) NOT NULL,
    `Date` datetime NOT NULL,
    PRIMARY KEY (`AutoId`),
    UNIQUE (`BillId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
ALTER TABLE `Bill` ADD INDEX (BillId);


-- --------------------------------------------------------

--
-- Table structure for table `Bill`
--

CREATE TABLE IF NOT EXISTS `Bill` (
    `AutoId` int(11) NOT NULL AUTO_INCREMENT,
    `BillId` varchar(50) NOT NULL,
    `RestaurantId` varchar(50) NOT NULL,
    `UserId` varchar(50) NOT NULL,
    `OrderId` varchar(50) NOT NULL,
    `Status` int(1) NOT NULL DEFAULT 0,
    `Price` DECIMAL(10, 2) NOT NULL,
    `NeedToPrintReceipt` TINYINT(1) NOT NULL,
    `ReceiptWasPrinted` TINYINT(1) NOT NULL,
    `Date` datetime NOT NULL,
    PRIMARY KEY (`AutoId`),
    UNIQUE (`BillId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
ALTER TABLE `Bill` ADD INDEX (BillId);


-- --------------------------------------------------------

--
-- Table structure for table `Menu`
--

CREATE TABLE IF NOT EXISTS `Menu` (
    `AutoId` int(11) NOT NULL AUTO_INCREMENT,
    `RestaurantId` varchar(50) NOT NULL,
    `MenuId` varchar(50) NOT NULL,
    PRIMARY KEY (`AutoId`),
    UNIQUE (`MenuId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
ALTER TABLE `Menu` ADD INDEX (MenuId);


-- --------------------------------------------------------

--
-- Table structure for table `MenuItem`
--

CREATE TABLE IF NOT EXISTS `MenuItem` (
    `AutoId` int(11) NOT NULL AUTO_INCREMENT,
    `RestaurantId` varchar(50) NOT NULL,
    `MenuItemId` varchar(50) NOT NULL,
    `MenuItemName` varchar(100) NOT NULL,
    `MenuItemDescription` varchar(1000) NOT NULL,
    `MenuItemImage` varchar(100) NOT NULL,
    `MenuItemPrice` decimal(10, 2) NOT NULL,
    `MenuItemProtein` decimal(10, 1) NOT NULL,
    `MenuItemCarbs` decimal(10, 1) NOT NULL,
    `MenuItemIsSpicy` tinyint(1) NOT NULL,
    `MenuItemIsVegetarian` tinyint(1) NOT NULL,
    `MenuItemIsVegan` tinyint(1) NOT NULL,
    `MenuItemIsGlutenFree` tinyint(1) NOT NULL,
    `MenuItemGroupId` int(10) NOT NULL,
    `MenuItemStatus` int(1) NOT NULL,
    PRIMARY KEY (`AutoId`),
    UNIQUE (`MenuItemId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
ALTER TABLE `MenuItem` ADD INDEX (MenuItemId);


-- --------------------------------------------------------

--
-- Table structure for table `Menu`
--

CREATE TABLE IF NOT EXISTS `User` (
    `AutoId` int(11) NOT NULL AUTO_INCREMENT,
    `UserId` varchar(50) NOT NULL,
    `LastName` varchar(30) NOT NULL,
    `FirstName` varchar(30) NOT NULL,
    `Username` varchar(30) NOT NULL,
    `Email` varchar(50) NOT NULL,
    `Password` varchar(50) NOT NULL,
    PRIMARY KEY (`AutoId`),
    UNIQUE (`UserId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
ALTER TABLE `User` ADD INDEX (UserId);


-- --------------------------------------------------------

--
-- Table structure for table `UserAtTable`
--

CREATE TABLE IF NOT EXISTS `UserAtTable` (
    `AutoId` int(11) NOT NULL AUTO_INCREMENT,
    `UserId` varchar(50) NOT NULL,
    `TableId` varchar(30) NOT NULL,
    `TableStatus` int(1) NOT NULL DEFAULT 0 COMMENT '',
    PRIMARY KEY (`AutoId`),
    UNIQUE (`UserId`, `TableId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
