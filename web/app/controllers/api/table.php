<?php

require_once 'restService.php';
require_once APP_PATH . 'models/TableModel.php';

class table extends restService {
    function __construct($params) {
        parent::__construct($params);
    }
    
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
                echo $row->TableStatus;
                if($row->TableStatus == TableModel::TABLE_STATUS_PAID)
                    $model->update(array('AutoId'=>$row->AutoId, 'TableStatus'=>TableModel::TABLE_STATUS_AWAITING_CLEANING));
            }
            else
                echo TableModel::TABLE_STATUS_ERROR;
        }
        echo 'Invalid URL';
    }

    protected function _setServiceName() {
        
    }

}
