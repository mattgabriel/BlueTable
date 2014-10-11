<?php

require_once 'restService.php';
require_once APP_PATH . 'models/OrderModel.php';
require_once APP_PATH . 'models/MenuItemInOrderModel.php';
require_once APP_PATH . 'models/MenuItemModel.php';

class order extends restService {
    
    public function postOrder($params)
    {
        $orderCreationParams = $params[ParamTypes::PAYLOAD];
        if (!empty($orderCreationParams)) {
            
            $orderModel = new OrderModel();
            $orderModel->OrderId = $orderCreationParams['OrderId'];
            $orderModel->UserId = $orderCreationParams['UserId'];
            $orderModel->TableId = $orderCreationParams['TableId'];
            $result = $orderModel->insert($orderModel);
            
            if (!empty($result)) {
                echo 'Order has been created.';
            } else {
                echo 'There was an error. Order was not created';
            }
        } else {
            echo 'Invalid URL';
        }
    }
    
    public function postMenuiteminorder($params)
    {
        $args = $params[ParamTypes::PAYLOAD];
        if (!empty($args)) {
            $menuItemInOrder = new MenuItemInOrder();
            $menuItemInOrder->OrderId = $args['OrderId'];
            $menuItemInOrder->MenuItemId = $args['MenuItemId'];
            $menuItemInOrder->insert($menuItemInOrder);
        } else {
            echo 'Bad payload';
        }
    }
    
    public function getPriceperorder($params) {
        $args = $params[ParamTypes::URI_PARAMS];
        $orderId = $args[0];
        if (!empty($args)) {
            $menuItemInOrder = new MenuItemInOrder();
            $priceResult = $menuItemInOrder->getTotalPriceForOrder($orderId);
            if (!empty($priceResult)) {
                echo $priceResult['getTotalPriceForOrder'];
            }
        } else {
            echo 'Invalid URL';
        }
    }

    protected function _setServiceName() {
        
    }

}
