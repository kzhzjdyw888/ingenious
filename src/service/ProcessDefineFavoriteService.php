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
use ingenious\db\ProcessDefine;
use ingenious\db\ProcessDefineFavorite;
use ingenious\db\ProcessInstance;
use ingenious\libs\base\BaseService;
use ingenious\libs\utils\ArrayHelper;
use ingenious\libs\utils\AssertHelper;
use ingenious\libs\utils\ModelUtils;
use ingenious\libs\utils\PageParam;
use ingenious\service\interface\ProcessDefineFavoriteServiceInterface;
use think\facade\Db;

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

    public function del(string|array|int $id): bool
    {
        $processType = new ProcessDefineFavorite();
        AssertHelper::notNull($id, '参数ID不能为空');
        $map1 = [];
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
        $processTaskActor = $this->get($param->id);
        ModelUtils::copyProperties($param, $processTaskActor);
        return $processTaskActor->save();
    }

    public function page(object $param): array
    {
        $map1 = [['pdf.favorite', '=', 1], ['pd.state', '=', 1], ['is_del', '=', 0]];
        if (!empty($param->user_id)) {
            $map1[] = ['pdf.user_id', '=', $param->user_id];
        }
        if (!empty($param->process_instance_id)) {
            $map1[] = ['pdf.process_define_id', '=', $param->process_define_id];
        }
        if (!empty($param->id)) {
            $map1[] = ['pdf.id', '=', $param->id];
        }
        $processDefineFavoriteTable = ProcessDefineFavorite::getTableName();
        $processDefineTable         = ProcessDefine::getTableName();
        [$page, $limit] = PageParam::getPageValue($param);
        $list = Db::table($processDefineFavoriteTable)
            ->alias('pdf')
            ->where($map1)
            ->field('pdf.*,pd.type_id,pd.name,pd.display_name,pd.description,pd.state,pd.content,pd.version')
            ->join([$processDefineTable => 'pd'], 'pdf.process_define_id = pd.id')
            ->order('pdf.create_time', 'desc');
        if ($page > 0 && $limit > 0) {
            $list = $list->page($page, $limit);
        }
        $list  = $list->select()->toArray();
        $count = Db::table($processDefineFavoriteTable)
            ->alias('pdf')
            ->where($map1)
            ->join([$processDefineTable => 'pd'], 'pdf.process_define_id = pd.id')
            ->count();
        return compact('list', 'count');
    }

    public function findById(string $id): ?ProcessDefineFavorite
    {
        return $this->get($id);
    }
}
