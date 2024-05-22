<?php


namespace app\adminapi\controller\wf\api;

use app\dao\system\admin\SystemDeptDao;
use app\services\system\admin\SystemDeptServices;
use ingenious\core\Execution;
use ingenious\enums\ProcessConst;
use ingenious\interface\AssignmentHandler;
use ingenious\libs\utils\ProcessFlowUtils;
use ingenious\model\TaskModel;


/**
 *
 * 申请人直属领导处理类
 * @author Mr.April
 * @since  1.0
 */
class AssignmentDirectManager implements AssignmentHandler
{

    public function assign(TaskModel $model, Execution $execution): array
    {
        $result = [];
        //1.0 当前流程实列
        $processInstance = $execution->getProcessInstance();//流程实列
        $operator        = $processInstance->getData('operator');//申请人ID
        $variable        = $processInstance->getData('variable');//流程实列变量
        $ext             = ProcessFlowUtils::variableToDict($variable);
        $deptId          = $ext->get(ProcessConst::USER_DEPT_ID, '');
        if (empty($deptId)) {
            //如果没有部门id 在此实现逻辑通过发起人找上级部门 待优化
            return $result;
        }
        $deptServices = new SystemDeptServices(new SystemDeptDao());
        $deptData     = $deptServices->get($deptId);
        if ($deptData == null) {
            return [];
        }
        $result[] = $deptData->getData('manager_id');
        return $result;
    }
}
