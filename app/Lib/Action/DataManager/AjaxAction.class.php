<?php
class AjaxAction extends Action
{
    public function _initialize()
    {

    }
    public function getData()
    {
        $value = array();
        $manageInfos = C('DataManageConfig');
        $tableId = $this->_request('tableId', 'trim', null);
        $stepKey = $this->_request('stepKey', 'trim', null);
        if(isset($tableId, $stepKey, $manageInfos[$tableId], $manageInfos[$tableId]['steps'][$stepKey])) {
            $stepInfo = $manageInfos[$tableId]['steps'][$stepKey];
            $value = $this->_getPrepareStepData($stepInfo);
        }
        echo json_encode($value);
    }

    /**
     * Get prepare step data
     * @param unknown_type $stepData
     * @param unknown_type $stepName
     * @param unknown_type $stepInfo
     * @return Ambigous <NULL, mixed, multitype:, boolean>
     */
    protected function _getPrepareStepData($stepInfo)
    {
        $data = null;
        $model = M($stepInfo['table']);
        if(isset($stepInfo['sql'])) {
            if(isset($stepInfo['sqlParam'])) {
                $paramList = array();
                foreach($stepInfo['sqlParam'] as $getKeyName) {
                    $paramList[] = I($getKeyName, null);
                }
                $stepInfo['sql'] = vsprintf($stepInfo['sql'], $paramList);
            }
            $data = $model->query($stepInfo['sql']);
            //echo $model->getLastSql();
        } else if(isset($stepInfo['column'])){
            $model->field(join(',', array_values($stepInfo['column'])));
            if(isset($stepInfo['columnMap'])) {
                $where = array();
                foreach($stepInfo['columnMap'] as $columnName=>$getKeyName) {
                    $where[]= $columnName . "='".I($getKeyName, '', 'addslashes') . "'";
                }
                $model->where(join(' AND ', $where));
            }
            $data = $model->select();
        }
        //echo $model->getLastSql();

        return $data;
    }
}
/* EOF */