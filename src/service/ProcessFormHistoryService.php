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

use ingenious\db\ProcessDesignForm;
use ingenious\db\ProcessFormHistory;
use ingenious\libs\base\BaseService;
use ingenious\libs\utils\ArrayHelper;
use ingenious\libs\utils\AssertHelper;
use ingenious\libs\utils\ModelUtils;
use ingenious\libs\utils\PageParam;
use ingenious\service\interface\ProcessFormHistoryServiceInterface;

class ProcessFormHistoryService extends BaseService implements ProcessFormHistoryServiceInterface
{

    protected function setModel(): string
    {
        return ProcessFormHistory::class;
    }

    public function create(object $param): bool
    {
        unset($param->id);
        $processFormBuilder = new ProcessDesignForm();
        ModelUtils::copyProperties($param, $processFormBuilder);
        return $processFormBuilder->save();
    }

    public function update(object $param): bool
    {
        AssertHelper::notNull($param->id ?? '', '参数ID不能为空');
        $processDefine = $this->get($param->id);
        ModelUtils::copyProperties($param, $processDefine);
        return $processDefine->save();
    }

    public function page(object $param): array
    {
        $where = ArrayHelper::paramsFilter($param, [
            ['name', ''],
            ['display_name', ''],
            ['is_del', '0'],
        ]);
        [$page, $limit] = PageParam::getPageValue($param);
        $list  = $this->selectList($where, '*', $page, $limit, 'create_time asc', [], true)->toArray();
        $count = $this->count($where);
        return compact('list', 'count');
    }

    public function findById(string $id): ?ProcessFormHistory
    {
        AssertHelper::notNull($param->id ?? '', '参数ID不能为空');
        return $this->get($id);
    }

    public function getLatestByProcessDesignId(string $processDesignId): mixed
    {
        AssertHelper::notNull($processDesignId, '参数process_design_id不能为空');
        return $this->selectList(['process_design_id' => $processDesignId], '*', 0, 0, 'create_time asc', [], true)->last();
    }
}
