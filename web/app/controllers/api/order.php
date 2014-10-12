<?php

require_once 'restService.php';
require_once APP_PATH . 'models/OrderModel.php';
require_once APP_PATH . 'models/MenuItemInOrderModel.php';
require_once APP_PATH . 'models/MenuItemModel.php';
require_once APP_PATH . 'models/UserAtTable.php';

class order extends restService {
    
    public function postOrder($params)
    {
        $orderCreationParams = (array)$params[ParamTypes::PAYLOAD];
        if (!empty($orderCreationParams)) {
            
            $orderModel = new OrderModel();
            $orderModel->OrderId = $orderCreationParams['OrderId'];
            $orderModel->UserId = $orderCreationParams['UserId'];
            $orderModel->TableId = $orderCreationParams['TableId'];
            $orderModel->Date =  date('Y-m-d H:i:s');
            $orderModel->TotalPrice =  0;
            $orderModel->RestaurantId =  'rest1234';
            $orderModel->MenuItemsIds = '';
            $result = $orderModel->insert($orderModel);
            
            $uatm = new UserAtTableModel();
            $hasRow = !empty($uatm->getTableByTableId($orderCreationParams['TableId']));
            
            if(!$hasRow)
            {
                $uatm->UserId = $orderCreationParams['UserId'];
                $uatm->TableId = $orderCreationParams['TableId'];
                $uatm->Status = UserAtTableModel::TABLE_STATUS_SITTING;
                $uatm->SeatedTime = date('Y-m-d H:i:s');
                $uatm->insert($uatm);
            }
            
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
        $args = (array)$params[ParamTypes::PAYLOAD];
        if (!empty($args)) {
            $menuItemInOrder = new MenuItemInOrder();
            $menuItemInOrder->OrderId = 
                    $args['OrderId'];
            $menuItemInOrder->MenuItemId = 
                    $args['MenuItemId'];
            $menuItemInOrder->insert($menuItemInOrder);
        } else {
            echo 'Bad payload';
        }
    }
    
    public function getPriceperorder($params) {
        $args = (array)$params[ParamTypes::PAYLOAD];
        $orderId = $args['OrderId'];
        if ($orderId) {
            $menuItemInOrder = new MenuItemInOrder();
            $priceResult = $menuItemInOrder->getTotalPriceForOrder($orderId);
            if (!empty($priceResult[0]['TotalPrice'])) {
                echo $priceResult[0]['TotalPrice'];
            }
        } else {
            echo 'Bad payload';
        }
    }
    
    public function deleteRemoveorderitems($params) {
        $args = (array)$params[ParamTypes::PAYLOAD];
        $orderId = $args['OrderId'];
        if ($orderId) {
            $menuItemInOrder = new MenuItemInOrder();
            $menuItemInOrder->deleteItemsByOrder($orderId);
        } else {
            echo 'Bad payload';
        }
    }
    
    public function putConcludeorder($params) {
        $args = (array)$params[ParamTypes::PAYLOAD];
        $orderId = $args['OrderId'];
        if ($orderId) {
            $orderModel = new OrderModel();
            $orderModel->closeOrder($orderId);
            
            
        } else {
            echo 'Bad payload';
        }
    }
    
    public function getListofitemsfororder($params) {
        $args = (array)$params[ParamTypes::PAYLOAD];
        $orderId = $args['OrderId'];
        if ($orderId) {
            $menuItemInOrder = new MenuItemInOrder();
            $itemsForOrder = $menuItemInOrder->returnListOfItemsForOrder($orderId);
            return json_encode($itemsForOrder);
        } else {
            echo 'Bad payload';
        }
    }

    protected function _setServiceName() {
        
    }

}
