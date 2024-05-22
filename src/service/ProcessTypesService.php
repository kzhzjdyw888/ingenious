<?php
/**
 *+------------------
 * Ingenious
 *+------------------
 * Copyright (c) https://gitee.com/ingenstream/ingenious  All rights reserved. 本版权不可删除，侵权必究
 *+------------------
 * Author: Mr. April (405784684@qq.com)
 *+------------------
 * Software Registration Number: 2024SR0694589
 * Official Website: http://www.ingenstream.cn
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

    public function del(string|array|int $id): bool
    {
        $processType = new ProcessType();
        $map1        = [];
        if (is_array($id)) {
            $map1[] = ['id', 'in', $id];
        } else {
            $map1[] = ['id', '=', $id];
        }
        return $processType->where($map1)->delete();
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
        $list = $this->selectList($where, '*', $page, $limit, 'sort desc', ['parent','processDefine'], true)->toArray();
        foreach ($list as $key => $value) {
            if (isset($value['parent']) && isset($value['parent']['name'])) {
                $list[$key]['pid_name'] = $value['parent']['name'];
            } else {
                $list[$key]['pid_name'] = '顶级';
            }
            unset($list[$key]['parent']);
        }
        $count = $this->count($where);
        return compact('list', 'count');
    }

    public function findById(string $id): ?ProcessType
    {
        return $this->get($id);
    }

    public function selectOptions(object $param): array
    {
        $where = ArrayHelper::paramsFilter($param, [
            ['is_del', 0],
            ['name', ''],
            ['status', ''],
            ['pid', ''],
        ]);
        return $this->selectList($where, 'id as value,name as label', 0, 0, 'name', [], true)->toArray();
    }

    public function selectTree(object $param): array
    {
        $where = ArrayHelper::paramsFilter($param, [
            ['is_del', 0],
            ['name', ''],
            ['status', ''],
            ['pid', ''],
        ]);
        // 获取根节点数据，并递归获取所有子节点数据
        $list  = $this->selectList($where, '*', 0, 0, 'sort desc', ['parent'], true)->toArray();
        $count = count($list);
        // 处理树形数据，将其转换为嵌套结构
        $list = $this->buildNestedTree($list);
        return compact('list', 'count');
    }

    private function buildNestedTree($data, $parentId = '0'): array
    {
        $tree = [];
        foreach ($data as $key => $value) {
            if ($value['pid'] == $parentId) {
                $value['pid_name'] = $value['parent']['name'] ?? '顶级';
                unset($value['parent']);

                $children = $this->buildNestedTree($data, $value['id']);
                if (!empty($children)) {
                    $value['children'] = $children;
                }
                $tree[] = $value;
            }
        }
        return $tree;
    }
}
