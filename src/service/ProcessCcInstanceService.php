<?php
/**
 *+------------------
 * Lflow
 *+------------------
 * Copyright (c) 2023~2030 gitee.com/liu_guan_qing All rights reserved.本版权不可删除，侵权必究
 *+------------------
 * Author: Mr.April(405784684@qq.com)
 *+------------------
 */

namespace ingenious\service;

use ingenious\db\ProcessCcInstance;
use ingenious\libs\base\BaseService;
use ingenious\libs\utils\ArrayHelper;
use ingenious\libs\utils\AssertHelper;
use ingenious\libs\utils\ModelUtils;
use ingenious\libs\utils\PageParam;
use ingenious\service\interface\ProcessCcInstanceServiceInterface;

/**
 *
 * 流程实例抄送服务类
 * @author Mr.April
 * @since  1.0
 */
class ProcessCcInstanceService extends BaseService implements ProcessCcInstanceServiceInterface
{

    protected function setModel(): string
    {
        return ProcessCcInstance::class;
    }

    public function save($param): bool
    {
        unset($param->id);
        $processCcInstance = new ProcessCcInstance();
        ModelUtils::copyProperties((object)$param, $processCcInstance);
        return $processCcInstance->save();
    }

    public function update($param): bool
    {
        AssertHelper::notNull($param->id ?? '', '参数ID不能为空');
        $processCcInstance = new ProcessCcInstance();
        ModelUtils::copyProperties((object)$param, $processCcInstance);
        return $processCcInstance->save();
    }

    public function findById(string $id):ProcessCcInstance
    {
        AssertHelper::notNull($param->id ?? '', '参数ID不能为空');
        return $this->get($id);
    }

    public function page($param): array
    {
        /** @var TYPE_NAME $where */
        $where = ArrayHelper::paramsFilter($param, [
            ['enabled', 1],
        ]);
        [$page, $limit] = PageParam::getPageValue($param);
        $list  = $this->selectList($where, '*', $page, $limit, 'create_time asc', [], true)->toArray();
        $count = $this->count($where);
        return compact('list', 'count');
    }
}
