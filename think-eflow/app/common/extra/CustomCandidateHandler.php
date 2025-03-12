<?php

namespace app\common\extra;


use madong\ingenious\interface\ICandidateHandler;
use madong\ingenious\model\TaskModel;
use madong\ingenious\parser\INodeParser;
use madong\interface\IDict;

/**
 *  候选人
 *
 * @author Mr.April
 * @since  1.0
 */
class CustomCandidateHandler implements ICandidateHandler
{


    public function handle(TaskModel $model): array
    {
        $array   = [];
        $extDict = $model->getExt();
        if ($extDict instanceof IDict) {
            // 1.0 获取候选人参与人用户id
            $array1 = explode(',', $extDict->get(INodeParser::EXT_FIELD_CANDIDATE_USERS_KET));

            // 处理 array1，将字符串数字转换为整数，保留其他字符串
            $array1 = array_map(function ($item) {
                $trimmedItem = trim($item); // 去掉空格
                return is_numeric($trimmedItem) ? (int)$trimmedItem : $trimmedItem; // 仅转换数字字符串
            }, $array1);

            $array2   = [];
            $groupKey = $extDict->get(INodeParser::EXT_FIELD_CANDIDATE_GROUPS_KEY);

            // 2.0 如果候选用户组不为空获取框
//            if (!empty($groupKey)) {
//                $roleModel = new SystemRole();
//                $array2    = $roleModel->where('role_id', 1)->column('admin_id');
//            }
            // 合并两个数组并去重
            $array = array_unique(array_merge($array1, $array2));
        }
        return $array;
    }

}
