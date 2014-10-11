<?php

require_once 'restService.php';
require_once APP_PATH . 'models/TableModel.php';
require_once APP_PATH . 'models/UserAtTableModel.php';

class table extends restService {
    public function getStatus($params)
    {
        $tableid = null;
        $ids = $params[ParamTypes::URI_PARAMS];
        if(!empty($ids)){
            $tableid = $ids[0];
            $model = new TableModel();
            $data = $model->getTableByTableId($tableid);
            if(!empty($data))
            {
                $row = $data[0];
                if($row->TableStatus == TableModel::TABLE_STATUS_PAID)
                    $model->update(array('AutoId'=>$row->AutoId, 'TableStatus'=>TableModel::TABLE_STATUS_AWAITING_CLEANING));
            }
            else
                echo TableModel::TABLE_STATUS_ERROR;
        }
        else
            echo 'Invalid URL';
    }
    
    public function postTable ($params)
    {
        $args = $params[ParamTypes::PAYLOAD];
        if(!empty($args))
        {
            $model = new UserAtTableModel();
            $model->TableId = $args['TableId'];
            $model->UserId = $args['UserId'];
            $model->SeatedTime = date('Y-m-d H:i:s');
            $model->Status = TableModel::TABLE_STATUS_SITTING;
            $model->insert($model);
        }
        else
            echo 'Bad payload';
    }

    protected function _setServiceName() {
        
    }

}
