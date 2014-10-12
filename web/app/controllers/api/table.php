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
            $model = new UserAtTableModel();
            $data = $model->getTableByTableId($tableid);
            if(!empty($data))
            {
                $row = $data[0];
                echo $row->TableStatus;
                if($row->TableStatus == UserAtTableModel::TABLE_STATUS_PAID)
                    $model->update(array('AutoId'=>$row->AutoId, 'TableStatus'=>UserAtTableModel::TABLE_STATUS_AWAITING_CLEANING));
            }
            else
                echo TableModel::TABLE_STATUS_AVAILABLE;
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
            $tables = $model->getTableByTableId($args['TableId']);
            foreach($tables as $table)
            {
                $uatm = new UserAtTableModel();
                $uatm->AutoId = $table->AutoId;
                $uatm->TableStatus = UserAtTableModel::TABLE_STATUS_PAID;
                $model->update($uatm);
            }
        }
        else
            echo 'Bad payload';
    }

    protected function _setServiceName() {
        
    }

}
