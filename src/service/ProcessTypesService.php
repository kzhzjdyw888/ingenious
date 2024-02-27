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

use ingenious\db\ProcessType;
use ingenious\libs\base\BaseService;
use ingenious\libs\utils\ArrayHelper;
use ingenious\libs\utils\AssertHelper;
use ingenious\libs\utils\ModelUtils;
use ingenious\libs\utils\PageParam;
use ingenious\service\interface\ProcessTypeServiceInterface;

class ProcessTypesService extends BaseService implements ProcessTypeServiceInterface
{

    protected function setModel(): string
    {
        return ProcessType::class;
    }

    public function create(object $param): bool
    {
        unset($param->id);
        $processType = new ProcessType();
        ModelUtils::copyProperties($param, $processType);
        return $processType->save();
    }

    public function update(object $param): bool
    {
        AssertHelper::notNull($param->id ?? '', '参数ID不能为空');
        $processType = $this->get($param->id);
        ModelUtils::copyProperties($param, $processType);
        return $processType->save();
    }

    public function page(object $param): array
    {
        /** @var TYPE_NAME $where */
        $where = ArrayHelper::paramsFilter($param, [
            ['is_del', 0],
            ['name', ''],
            ['status', ''],
            ['pid', ''],
        ]);
        [$page, $limit] = PageParam::getPageValue($param);
        $list  = $this->selectList($where, '*', $page, $limit, 'sort desc', [], true)->toArray();
        $count = $this->count($where);
        return compact('list', 'count');
    }

    public function findById(string $id): ?ProcessType
    {
        return $this->get($id);
    }
}
