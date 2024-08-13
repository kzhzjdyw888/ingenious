<?php
/**
 * Copyright (C) 2024 Ingenstream
 * This software is licensed under the Apache-2.0 license.
 * A copy of the license can be found at http://www.apache.org/licenses/LICENSE-2.0
 * Official Website: http://www.ingenstream.cn
 * Author: Mr. April <405784684@qq.com>
 * Project: Ingenious
 * Repository: https://gitee.com/ingenstream/ingenious
 */

namespace ingenious\service;

use ingenious\db\ProcessTaskActor;
use ingenious\libs\base\BaseService;
use ingenious\libs\utils\AssertHelper;
use ingenious\libs\utils\ModelUtils;
use ingenious\service\interface\ProcessTaskActorServiceInterface;

class ProcessTaskActorService extends BaseService implements ProcessTaskActorServiceInterface
{

    protected function setModel(): string
    {
        return ProcessTaskActor::class;
    }

    public function create(object $param): bool
    {
        unset($param->id);
        $processTaskActor = new ProcessTaskActor();
        ModelUtils::copyProperties($param, $processTaskActor);
        return $processTaskActor->save();
    }

    public function update(object $param): bool
    {
        AssertHelper::notNull($param->id ?? '', '参数ID不能为空');
        $processTaskActor = $this->get($param->id);
        ModelUtils::copyProperties($param, $processTaskActor);
        return $processTaskActor->save();
    }

    public function page(object $param): array
    {}

    public function findById(string $id): ?ProcessTaskActor
    {
        return $this->get($id);
    }
}
