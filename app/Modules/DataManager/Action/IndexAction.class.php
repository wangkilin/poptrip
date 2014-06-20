<?php
class IndexAction extends Action
{
    // if this is set, it will load the specified table to be manage.
    protected $autoLoadDefaultTable = 0;
    protected $manageInfos;
    protected $tableManageIndex;

    protected $isListFilterLoaded = true;
    protected $theFinalFilterStepName = null;

    protected $stepIndex = 0;

    private $dbModel = null;


    public function _initialize()
    {
        $this->manageInfos = C('DataManageConfig');
        if(!isset($this->autoLoadDefaultTable, $this->manageInfos[$this->autoLoadDefaultTable])) {
            $this->autoLoadDefaultTable = null;
        }
        if(count($this->manageInfos)==0) {
            $this->error('ErrorHasNoTableInConfigFile');
            return;
        }
        $actionName = $this->getActionName();
        $this->tableManageIndex = $this->_request('tableId', 'trim', null);
        foreach(get_defined_constants() as $k=>$v) {
            //echo "$k=>$v<br/>";
        }
        //$this->tableManageIndex--;
        // list all tables to be managed
        if(!isset($this->tableManageIndex, $this->manageInfos)) {
            $this->tableManageIndex = null;
            if(isset($this->autoLoadDefaultTable)) {
                $this->tableManageIndex = $this->autoLoadDefaultTable;
            }
        }

        // no tableIndex, and not index action, goes into index action
        if(!isset($this->tableManageIndex) && strtolower(ACTION_NAME)!='index') {
            $this->redirect(GROUP_NAME . '/' . MODULE_NAME . '/index');
        }

        $this->assign('ManageInfos', $this->manageInfos);
        $this->assign('TableManageIndex', $this->tableManageIndex);


        if(strtolower(ACTION_NAME)!='index') {
            $this->manageTable();
            exit;
        }
    }

    public function index()
    {
        $this->assign('TableManageIndex', null);
        $this->display();
    }

    public function addItem()
    {
        $model = M($this->manageInfo['table']);
        $manageInfo = $this->manageInfo;
        if($this->manageInfo['method']!='defined') {
            $manageInfo['crudKey'] = $model->getPk();
            $manageInfo['crudKey'] = array($manageInfo['crudKey']);
        }
            $manageInfo['columnTitles'] = isset($manageInfo['columnTitles']) ? $manageInfo['columnTitles'] : array();
            $manageInfo['edit'] = isset($manageInfo['edit']) ? $manageInfo['edit'] : array();
            $manageInfo['edit']['column'] = isset($manageInfo['edit']['column']) ? $manageInfo['edit']['column'] : $model->getDbFields();
            $manageInfo['edit']['columnMap'] = isset($manageInfo['edit']['columnMap']) ? $manageInfo['edit']['columnMap'] : array($manageInfo['crudKey'][0]=>$manageInfo['crudKey'][0]);
            $this->manageInfo = $manageInfo;
            $this->manageInfos[$this->tableManageIndex] = $manageInfo;
            $this->assign('ManageInfos', $this->manageInfos);
        //}
        $tableFields = $model->query('desc ' . $this->manageInfo['table']);
        if(isset($_POST['submitAddItem'])) {
            $where = array();
            $data = array();
            foreach($tableFields as $_field) {
                $_value = I('post.'.$_field['Field'], null, 'addslashes');
                $data[$_field['Field']] = $_value;
            }
            $linkParams = array('tableId'=>$this->tableManageIndex, 'stepIndex'=>count($this->manageInfo['listFilter']['step']));
            foreach($this->manageInfo['listFilter']['step'] as $_step) {
                if(is_string($_step)) $_step = array($_step);
                foreach($_step as $__step) {
                    $linkParams[$__step] = I($__step, '');
                }
            }
            if(C('VAR_PAGE', null) && I(C('VAR_PAGE'), null)) {
                $linkParams[C('VAR_PAGE')] = I(C('VAR_PAGE'));
            }
            if(false!==$model->add($data)) {
                $this->redirect(GROUP_NAME.'/'.MODULE_NAME.'/manageTable', $linkParams, 5,
                            sprintf('Adding successfully!'));
                return false;
            } else {
                $linkParams = array_merge($linkParams, $where);
                $this->redirect(GROUP_NAME.'/'.MODULE_NAME.'/'.ACTION_NAME, $linkParams, 5,
                            sprintf('Adding failed. :( '));
                return false;
            }
        }

        $editInfo = $this->manageInfo['edit'];
        $steps = isset($editInfo['prepare']) ? $editInfo['prepare'] : array();
        $prepareData = array();
        foreach($steps as $_step) {
            $prepareData[$_step] = $this->_getPrepareStepData($this->manageInfo['steps'][$_step]);
        }
        $this->assign('PrepareData', $prepareData);
        $this->assign('EditInfo', $editInfo);
        $this->assign('FilterSteps', isset($this->manageInfo['listFilter']) ? $this->manageInfo['listFilter']['step'] : array());
        $this->assign('TableFields', $tableFields);
        $this->assign('CrudFieldChild', isset($this->manageInfo['crudFieldChild']) ? $this->manageInfo['crudFieldChild'] : array());
        $this->assign('MainContent', $this->fetch(ACTION_NAME));
    }

    public function editItem()
    {
        $model = M($this->manageInfo['table']);
        $tableFields = $model->query('desc ' . $this->manageInfo['table']);
        $manageInfo = $this->manageInfo;
        if($this->manageInfo['method']!='defined') {
            $manageInfo['crudKey'] = $model->getPk();
            $manageInfo['crudKey'] = array($manageInfo['crudKey']);
        }
            $manageInfo['columnTitles'] = isset($manageInfo['columnTitles']) ? $manageInfo['columnTitles'] : array();
            $manageInfo['edit'] = isset($manageInfo['edit']) ? $manageInfo['edit'] : array();
            $manageInfo['edit']['column'] = isset($manageInfo['edit']['column']) ? $manageInfo['edit']['column'] : $model->getDbFields();
            $manageInfo['edit']['columnMap'] = isset($manageInfo['edit']['columnMap']) ? $manageInfo['edit']['columnMap'] : array($manageInfo['crudKey'][0]=>$manageInfo['crudKey'][0]);
            $this->manageInfo = $manageInfo;
            $this->manageInfos[$this->tableManageIndex] = $manageInfo;
            $this->assign('ManageInfos', $this->manageInfos);
        //}

        if(isset($_POST['submitEditItem'])) {
            $where = array();
            $data = array();
            foreach($tableFields as $_field) {
                $_value = I('post.'.$_field['Field'], null, 'addslashes');
                if(in_array($_field['Field'], $this->manageInfo['crudKey'])) {
                    if(null===$_value) {
                        $this->redirect(GROUP_NAME.'/'.MODULE_NAME.'/'.ACTION_NAME, array('tableId'=>$this->tableManageIndex), 5,
                            sprintf('Update failed. Parameter: missing crudKey parameter[%s]!', $_key));
                        return false;
                    }
                    $where[$_key] = $_value;
                }
                if(null===$_value) {
                    continue;
                }
                $data[$_field['Field']] = $_value;
            }
            $linkParams = array('tableId'=>$this->tableManageIndex, 'stepIndex'=>count($this->manageInfo['listFilter']['step']));
            foreach($this->manageInfo['listFilter']['step'] as $_step) {
                if(is_string($_step)) $_step = array($_step);
                foreach($_step as $__step) {
                    $linkParams[$__step] = I($__step, '');
                }
            }
            if(C('VAR_PAGE', null) && I(C('VAR_PAGE'), null)) {
                $linkParams[C('VAR_PAGE')] = I(C('VAR_PAGE'));
            }
            if(false!==$model->save($data, $where)) {
                $this->redirect(GROUP_NAME.'/'.MODULE_NAME.'/manageTable', $linkParams, 5,
                            sprintf('Update successfully!'));
                return false;
            } else {
                $linkParams = array_merge($linkParams, $where);
                $this->redirect(GROUP_NAME.'/'.MODULE_NAME.'/'.ACTION_NAME, $linkParams, 5,
                            sprintf('Update failed. :( '));
                return false;
            }
        }
        foreach($this->manageInfo['crudKey'] as $_key) {
            $_value = I($_key, null, 'addslashes');
            if(null===$_value) {
                $this->redirect(GROUP_NAME.'/'.MODULE_NAME.'/index', array(), 5,
                        sprintf('Parameter: missing crudKey parameter[%s]!', $_key));
                return false;
            }
            //$model->where("$_key = '$_value'" );
        }
        //$editData = $model->select();
        $editData = $this->_getItemData();
        if(!$editData) {
            $this->redirect(GROUP_NAME.'/'.MODULE_NAME.'/index', array(), 5,
                    sprintf('No data: not found the item to edit!'));
            return false;
        }

        $editInfo = $this->manageInfo['edit'];
        $steps = isset($editInfo['prepare']) ? $editInfo['prepare'] : array();
        $prepareData = array();
        // prepare data. it will be used in edit form.
        foreach($steps as $_step) {
            // this is ajax request
            if(isset($this->manageInfo['steps'][$_step]['ajax'])) {
                $prepareData[$_step] = array();
                continue;
            }
            $prepareData[$_step] = $this->_getPrepareStepData($this->manageInfo['steps'][$_step]);
        }
        $this->assign('EditData', $editData);
        $this->assign('PrepareData', $prepareData);
        $this->assign('EditInfo', $editInfo);
        $this->assign('FilterSteps', isset($this->manageInfo['listFilter']) ? $this->manageInfo['listFilter']['step'] : array());
        $this->assign('TableFields', $tableFields);
        $this->assign('CrudFieldChild', isset($this->manageInfo['crudFieldChild']) ? $this->manageInfo['crudFieldChild'] : array());

        $this->assign('MainContent', $this->fetch(ACTION_NAME));
    }

    public function _getItemData()
    {
        $editConfig = $this->manageInfo['edit'];
        $data = array();
        $model = M($this->manageInfo['table']);
        if(isset($editConfig['sql'])) {
            if(isset($editConfig['sqlParam'])) {
                $paramList = array();
                foreach($listConfig['sqlParam'] as $getKeyName) {
                    $paramList[] = I($getKeyName, null);
                }
                $editConfig['sql'] = vsprintf($editConfig['sql'], $paramList);
            }
            $data = $model->query($listConfig['sql']);
            //echo $model->getLastSql();
        } else {
            if(isset($editConfig['column'])) {
                $model->field(join(',', array_values($listConfig['column'])));
            }
            if(isset($editConfig['columnMap'])) {
                $where = array();
                foreach($editConfig['columnMap'] as $columnName=>$getKeyName) {
                    $where[]= $columnName . "='".I($getKeyName, '', 'addslashes') . "'";
                }
                $model->where(join(' AND ', $where));
            }
            $data = $model->select();
        }
        $data = isset($data[0]) ? $data[0] : array();

        return $data;
    }

    public function deleteItem()
    {
        $model = M($this->manageInfo['table']);

        $where = array();
        foreach($this->manageInfo['crudKey'] as $_key) {
            $_value = I($_key, null, 'addslashes');
            if(null===$_value) {
                $this->redirect(GROUP_NAME.'/'.MODULE_NAME.'/index', array(), 5,
                        sprintf('Parameter: missing crudKey parameter[%s] to delete line!', $_key));
                return false;
            }
            $model->where("$_key = '$_value'" );
            //$where[$_key] = $_value;
        }

        $linkParams = array('tableId'=>$this->tableManageIndex, 'stepIndex'=>count($this->manageInfo['listFilter']['step']));
        foreach($this->manageInfo['listFilter']['step'] as $_step) {
            if(is_string($_step)) $_step = array($_step);
            foreach($_step as $__step) {
                $linkParams[$__step] = I($__step, '');
            }
        }
        if(C('VAR_PAGE', null) && I(C('VAR_PAGE'), null)) {
            $linkParams[C('VAR_PAGE')] = I(C('VAR_PAGE'));
        }
        if(false!==$model->delete($where)) {
            $this->redirect(GROUP_NAME.'/'.MODULE_NAME.'/manageTable', $linkParams, 5,
                    sprintf('Delete line successfully!'));
            return false;
        } else {
            $linkParams = array_merge($linkParams, $where);
            $this->redirect(GROUP_NAME.'/'.MODULE_NAME.'/'.ACTION_NAME, $linkParams, 5,
                    sprintf('Delete line failed. :( '));
            return false;
        }
    }

    public function manageTable()
    {
        $this->manageInfo = $this->manageInfos[$this->tableManageIndex];

        switch(ACTION_NAME) {
            case 'addItem':
                $this->addItem();
                break;

            case 'editItem':
                $this->editItem();
                break;

            case 'deleteItem':
                $this->deleteItem();
                break;

            default:
                $this->listItem();
                break;
        }

        //$dataManagerModel = D('DataManager');
        $this->display('manageTable');
        //echo $this->_get('name');
    }

    public function error ()
    {

    }

    protected function _listAuto($prepareData=array())
    {
        $model = M($this->manageInfo['table']);
        $manageInfo = $this->manageInfo;
        $listConfig = isset($this->manageInfo['list']) ? $this->manageInfo['list'] : array();
        $listConfig['listColumns'] = isset($listConfig['listColumns']) ? $listConfig['listColumns'] : $model->getDbFields();
        $listConfig['replaceMap'] = isset($listConfig['replaceMap']) ? $listConfig['replaceMap'] : array();
        $manageInfo['crudKey'] = $model->getPk();
        $manageInfo['crudKey'] = array($manageInfo['crudKey']);
        $manageInfo['columnTitles'] = isset($manageInfo['columnTitles']) ? $manageInfo['columnTitles'] : array();
        $this->manageInfo = $manageInfo;

        $data = array();
        if(isset($listConfig['columnMap'])) {
            $where = array();
            foreach($listConfig['columnMap'] as $columnName=>$getKeyName) {
                $where[]= $columnName . "='".I($getKeyName, '', 'addslashes') . "'";
            }
            $model->where(join(' AND ', $where));
        }
        $count      = $model->count();// 查询满足要求的总记录数
        if(!empty($where)) {
            $model->where(join(' AND ', $where));
        }
        if(isset($listConfig['orderBy'])) {
            foreach($listConfig['orderBy'] as $_orderBy) {
                $model->order($_orderBy);
            }
        }

        import('ORG.Util.Page');// 导入分页类

        $Page       = new Page($count,C('DISPLAY_NUMBER_PERPAGE', 20));// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $model->limit($Page->firstRow.','.$Page->listRows);
        $this->assign('PageGuide',$show);// 赋值分页输出

        $data = $model->select();
        //echo $model->getLastSql();

        settype($data, 'array');
        $this->assign('Data', $data);
        $this->assign('ListColumns', $listConfig['listColumns']);
        $this->assign('ReplaceMap', $listConfig['replaceMap']);
        $this->assign('ReplaceRef', $prepareData);
        $this->assign('CrudKey', $this->manageInfo['crudKey']);
        $columnTitles = isset($listConfig['columnTitles']) ? $listConfig['columnTitles'] : array();
        $globalColumnTitles = isset($this->manageInfo['columnTitles']) ? $this->manageInfo['columnTitles'] : array();
        $this->assign('ColumnTitles', array_merge($globalColumnTitles, $columnTitles));

        return $this->fetch('list');
    }

    public function listItem()
    {
        // check if has list filter
        $filterSteps = array();
        if(isset($this->manageInfo['listFilter'], $this->manageInfo['listFilter']['step'])) {
            $filterContent = $this->_loadFilter($this->manageInfo['listFilter']);
            $this->assign('FilterContent', $filterContent);
            $filterSteps = $this->manageInfo['listFilter']['step'];
        }
        $this->assign('FilterSteps', $filterSteps);
        $stop = false;
        if($this->isListFilterLoaded && ($this->theFinalFilterStepName===null
                || I($this->theFinalFilterStepName,null)!==null)){
            $prepareContent = array();
            foreach($this->manageInfo['list']['prepare'] as $_stepInfo) {
                $prepareContent[$_stepInfo] = $this->_getPrepareStepData($this->manageInfo['steps'][$_stepInfo]);
                $_tmpArray = array();
                foreach($prepareContent[$_stepInfo] as $_dataInfo) {
                    $key = array_shift($_dataInfo);
                    $value = array_shift($_dataInfo);
                    $_tmpArray[$key] = $value;
                }
                $prepareContent[$_stepInfo] = $_tmpArray;
                unset($_tmpArray);
            }
            //var_dump($prepareContent);
            //$this->assign('ListContent', $listContent);
            if($this->manageInfo['method']=='defined') {
                $this->assign('MainContent', $this->_loadlistDefined($prepareContent));
            } else {
                $this->assign('MainContent', $this->_listAuto($prepareContent));
            }
        }
    }

    protected function _loadlistDefined($prepareData=array())
    {
        $listConfig = $this->manageInfo['list'];

        $data = array();
        $model = M($this->manageInfo['table']);
        if(isset($listConfig['sql'])) {
            if(isset($listConfig['sqlParam'])) {
                $paramList = array();
                foreach($listConfig['sqlParam'] as $getKeyName) {
                    $paramList[] = I($getKeyName, null);
                }
                $listConfig['sql'] = vsprintf($listConfig['sql'], $paramList);
            }
            $fromPos = stripos($listConfig['sql'], ' from ');
            $countSql = 'SELECT COUNT(*) ' . substr($listConfig['sql'], $fromPos);
            import('ORG.Util.Page');// 导入分页类
            $count      = $model->query($countSql);
            $count = array_shift($count[0]);
            $Page       = new Page($count,C('DISPLAY_NUMBER_PERPAGE', 20));// 实例化分页类 传入总记录数和每页显示的记录数
            $show       = $Page->show();// 分页显示输出
            // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $listConfig['sql'] .= ' limit ' . $Page->firstRow.','.$Page->listRows;
            $this->assign('PageGuide',$show);// 赋值分页输出
            $data = $model->query($listConfig['sql']);
            //echo $model->getLastSql();
        } else {
            $where = array();
            if(isset($listConfig['columnMap'])) {
                foreach($listConfig['columnMap'] as $columnName=>$getKeyName) {
                    $where[]= $columnName . "='".I($getKeyName, '', 'addslashes') . "'";
                }
                $model->where(join(' AND ', $where));
            }
            $count      = $model->count();// 查询满足要求的总记录数
            if($where) {
                $model->where(join(' AND ', $where));
            }
            if(isset($listConfig['orderBy'])) {
                foreach($listConfig['orderBy'] as $_orderBy) {
                    $model->order($_orderBy);
                }
            }
            import('ORG.Util.Page');// 导入分页类
            if(isset($listConfig['column'])) {
                $model->field(join(',', array_values($listConfig['column'])));
            }
            $Page       = new Page($count,C('DISPLAY_NUMBER_PERPAGE', 20));// 实例化分页类 传入总记录数和每页显示的记录数
            $show       = $Page->show();// 分页显示输出
            // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $model->limit($Page->firstRow.','.$Page->listRows);
            $this->assign('PageGuide',$show);// 赋值分页输出
            $data = $model->select();
        }
        //echo $model->getLastSql();

        settype($data, 'array');
        $this->assign('Data', $data);
        $this->assign('ListColumns', $listConfig['listColumns']);
        $this->assign('ReplaceMap', $listConfig['replaceMap']);
        $this->assign('ReplaceRef', $prepareData);
        $this->assign('CrudKey', $this->manageInfo['crudKey']);
        $columnTitles = isset($listConfig['columnTitles']) ? $listConfig['columnTitles'] : array();
        $globalColumnTitles = isset($this->manageInfo['columnTitles']) ? $this->manageInfo['columnTitles'] : array();
        $this->assign('ColumnTitles', array_merge($globalColumnTitles, $columnTitles));

        return $this->fetch('list');
    }

    protected function _loadFilter($filtersList)
    {
        $this->stepIndex = I('stepIndex', 0, 'intval');
        if ($this->stepIndex > count($filtersList['step'])) {
            $this->redirect(GROUP_NAME.'/'.MODULE_NAME.'/index', array(), 5,
                    sprintf('Parameter: stepIndex is wrong! No step[%d]  in table[%d].', $this->stepIndex, $this->tableManageIndex));
            return false;
        }
        $steps = $filtersList['step'];
        $stepsInfo = $this->manageInfo['steps'];
        $_stepIndex = 0;
        $stepData = array();
        // get step data.
        foreach($steps as $_step) {
            if(is_string($_step)) {
                $this->theFinalFilterStepName = $_step;
                // check if has prepared previous step data
                if(isset($stepsInfo[$_step]['columnMap'])) {
                    $columnsMap = $stepsInfo[$_step]['columnMap'];
                    foreach($columnsMap as $_map) {
                        if(!isset($stepData[$_map])) break 2;
                    }
                }

                $stepInfo = $stepsInfo[$_step];
                $stepData[$_step] = $this->_getFilterStepData($stepData, $__step, $stepInfo);
                if($stepData[$_step]===null) {
                    break ;
                }
            } else if(is_array($_step)) {
                foreach($_step as $__step) {
                    $this->theFinalFilterStepName = $__step;
                    // check if has prepared previous step data
                    if(isset($stepsInfo[$_step]['columnMap'])) {
                        $columnsMap = $stepsInfo[$_step]['columnMap'];
                        foreach($columnsMap as $_map) {
                            if(!isset($stepData[$_map])) break 2;
                        }
                    }

                    $stepInfo = $stepsInfo[$__step];
                    $stepData[$__step] = $this->_getFilterStepData($stepData, $__step, $stepInfo);
                    if($stepData[$__step]===null) {
                        break 2;
                    }
                }
            }
            if($this->stepIndex==$_stepIndex) {
                break;
            }
            $_stepIndex++;
        }

        if(($this->stepIndex)<count($filtersList['step'])) {
            // tell list management that filter has not been loaded completely
            $this->isListFilterLoaded = false;
            $this->theFinalFilterStepName = null;
        }

        $filterTemplate = isset($filtersList['filterTemplate']) ? $filtersList['filterTemplate'] : 'listFilter';
        $this->assign('FilterSteps', $filtersList['step']);
        $this->assign('FilterStepsSubmit', $filtersList['stepForm']);
        $this->assign('FilterData', $stepData);
        $this->assign('StepIndex', $this->stepIndex);
        $this->assign('StepsInfo', $stepsInfo);

        return $this->fetch($filterTemplate);
    }

    /**
     * Get list filter step data
     * @param unknown_type $stepData
     * @param unknown_type $stepName
     * @param unknown_type $stepInfo
     * @return Ambigous <NULL, mixed, multitype:, boolean>
     */
    protected function _getFilterStepData($stepData, $stepName, $stepInfo)
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
            if(isset($stepInfo['orderBy'])) {
                foreach($stepInfo['orderBy'] as $_orderBy) {
                    $model->order($_orderBy);
                }
            }
            $data = $model->select();
        }
        //echo $model->getLastSql();

        return $data;
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

    /**
     * Check if the specified filter step is valid
     * @param unknown_type $stepIndex
     * @param unknown_type $preparedData
     * @return boolean
     */
    protected function _checkFilterValid($stepIndex, $preparedData)
    {
        $steps = $this->manageInfo['listFilter']['step'];
        $stepSize = count($steps);
        // if has a valid step number
        if ($stepIndex >= count($steps)) {
            return false;
        }

        foreach($steps as $_step) {
            if(is_string($_step)) {
                $_step = array($_step);
            }
            foreach($_step as $__step) {
                // check if the specified step existing
                if(!isset($this->manageInfo['listFilter'][$__step])) {
                    return false;
                }
            }
        }




        return true;
    }

    public function getList ()
    {

    }

}
?>