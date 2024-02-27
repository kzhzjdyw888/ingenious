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

use ingenious\db\ProcessTaskActor;
use ingenious\db\ProcessType;
use ingenious\libs\base\BaseService;
use ingenious\libs\utils\AssertHelper;
use ingenious\libs\utils\ModelUtils;
use ingenious\service\interface\ProcessTaskActorServiceInterface;

class ProcessTypesService extends BaseService implements ProcessTaskActorServiceInterface
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
    }

    public function findById(string $id): ?ProcessTaskActor
    {
        return $this->get($id);
    }
}
