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

use ingenious\db\ProcessSurrogate;
use ingenious\enums\IsDeleteEnum;
use ingenious\enums\IsEnabledEnum;
use ingenious\enums\ProcessConst;
use ingenious\enums\ProcessDefineStateEnum;
use ingenious\libs\base\BaseService;
use ingenious\libs\utils\ArrayHelper;
use ingenious\libs\utils\AssertHelper;
use ingenious\libs\utils\ModelUtils;
use ingenious\libs\utils\PageParam;
use ingenious\service\interface\ProcessSurrogateServiceInterface;
use think\db\Query;

class ProcessSurrogateService extends BaseService implements ProcessSurrogateServiceInterface
{

    protected function setModel(): string
    {
        return ProcessSurrogate::class;
    }

    public function create(object $param): bool
    {
        unset($param->id);
        $processSurrogate = new ProcessSurrogate();
        ModelUtils::copyProperties($param, $processSurrogate);
        return $processSurrogate->save();
    }

    public function update(object $param): bool
    {
        AssertHelper::notNull($param->id ?? '', '参数ID不能为空');
        $processSurrogate = $this->get($param->id);
        ModelUtils::copyProperties($param, $processSurrogate);
        return $processSurrogate->save();
    }

    public function del(string|array|int $id): bool
    {
        $processType = new ProcessSurrogate();
        $map1        = [];
        if (is_array($id)) {
            $map1[] = ['id', 'in', $id];
        } else {
            $map1[] = ['id', '=', $id];
        }
        return $processType->where($map1)->delete();
    }

    public function page(object $param): array
    {

        $where = ArrayHelper::paramsFilter($param, [
            ['process_name', ''],
            ['operator', $param->{ProcessConst::CREATE_USER} ?? ''],
            ['surrogate', ''],
            ['enabled', ''],
        ]);
        [$page, $limit] = PageParam::getPageValue($param);
        $list  = $this->selectList($where, '*', $page, $limit, 'create_time asc', ['processDefine' => function (Query $query) {
            $query->where('state', ProcessDefineStateEnum::ENABLE[0]);
            $query->where('is_del', IsDeleteEnum::NO[0]);
        }], true)->toArray();
        $count = $this->count($where);
        return compact('list', 'count');
    }

    public function findById(string $id): ?ProcessSurrogate
    {
        return $this->get($id);
    }

    public function getSurrogate(string $operator, string $processName): ?string
    {
        $map1                 = [
            'operator'     => $operator,
            'process_name' => $processName,
            'enabled'      => IsEnabledEnum::YES[0],
        ];
        $processSurrogateList = $this->selectList($map1, '*', 0, 0, 'create_time desc', [], true);
        if (empty($processSurrogateList)) {
            return null;
        }
        foreach ($processSurrogateList as $processSurrogate) {
            // 为空表示全部，优先级最高，直接返回
            if (empty($processSurrogate->getData('process_name'))) {
                // 开始时间判断
                if (!is_null($processSurrogate->getData('start_time')) && $processSurrogate->getData('start_time') > time()) {
                    continue;
                }
                // 结束时间判断
                if (!is_null($processSurrogate->getData('end_time')) && $processSurrogate->getData('end_time') < time()) {
                    continue;
                }
                return $processSurrogate->getData('surrogate');
            }
        }
        // 取满足条件的最新一条
        $filteredSurrogates = array_filter($processSurrogateList->all(), function ($processSurrogate) use ($processName) {
            // 只查询流程名称一样的
            if ($processSurrogate->getData('process_name') !== $processName) {
                return false;
            }
            // 开始时间判断
            if (!is_null($processSurrogate->getData('start_time')) && $processSurrogate->getData('start_time') > time()) {
                return false;
            }
            // 结束时间判断
            if (!is_null($processSurrogate->getData('end_time')) && $processSurrogate->getData('end_time') < time()) {
                return false;
            }
            return true;
        });
        if (!empty($filteredSurrogates)) {
            $filteredSurrogate = reset($filteredSurrogates); // 获取第一个满足条件的元素
            return $filteredSurrogate->getData('surrogate');
        }
        return null;
    }
}
