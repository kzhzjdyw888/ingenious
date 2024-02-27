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

use ingenious\db\ProcessDefineFavorite;
use ingenious\libs\base\BaseService;
use ingenious\libs\utils\ArrayHelper;
use ingenious\libs\utils\AssertHelper;
use ingenious\libs\utils\ModelUtils;
use ingenious\libs\utils\PageParam;
use ingenious\service\interface\ProcessDefineFavoriteServiceInterface;

class ProcessDefineFavoriteService extends BaseService implements ProcessDefineFavoriteServiceInterface
{

    protected function setModel(): string
    {
        return ProcessDefineFavorite::class;
    }

    public function create(object $param): bool
    {
        unset($param->id);
        $processDefineFavorite = new ProcessDefineFavorite();
        ModelUtils::copyProperties($param, $processDefineFavorite);
        return $processDefineFavorite->save();
    }

    public function update(object $param): bool
    {
        AssertHelper::notNull($param->id ?? '', '参数ID不能为空');
        $processTaskActor = $this->get($param->id);
        ModelUtils::copyProperties($param, $processTaskActor);
        return $processTaskActor->save();
    }

    public function page(object $param): array
    {
        $where = ArrayHelper::paramsFilter($param, [
            ['user_id'],
        ]);
        [$page, $limit] = PageParam::getPageValue($param);
        $list  = $this->selectList($where, '*', $page, $limit, 'create_time asc', [], true)->toArray();
        $count = $this->count($where);
        return compact('list', 'count');
    }

    public function findById(string $id): ?ProcessDefineFavorite
    {
        return $this->get($id);
    }
}
