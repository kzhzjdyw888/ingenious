<?php

namespace app\common\extra;

use app\common\model\AdminAdminRole as M;
use madong\ingenious\interface\IAssignment;
use madong\ingenious\interface\IExecution;
use madong\ingenious\model\TaskModel;

/**
 * 默认参与者处理类
 *
 * @author Mr.April
 * @since  1.0
 */
class AssignmentDefault implements IAssignment
{


    public function assign(TaskModel $model, IExecution $execution): array
    {
        $key = $model->getGroupKey();
        if (empty($key)) {
            return [];
        }
        $model = new M();
        return $model->where('role_id', $key)->column('admin_id');
    }
}
