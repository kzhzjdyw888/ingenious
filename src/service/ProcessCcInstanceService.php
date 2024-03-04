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

use http\Params;
use ingenious\db\ProcessCcInstance;
use ingenious\db\ProcessDefine;
use ingenious\db\ProcessDesign;
use ingenious\db\ProcessInstance;
use ingenious\libs\base\BaseService;
use ingenious\libs\utils\ArrayHelper;
use ingenious\libs\utils\AssertHelper;
use ingenious\libs\utils\DateTimeHelper;
use ingenious\libs\utils\ModelUtils;
use ingenious\libs\utils\PageParam;
use ingenious\libs\utils\ProcessFlowUtils;
use ingenious\service\interface\ProcessCcInstanceServiceInterface;
use think\facade\Db;

/**
 * 流程实例抄送服务类
 *
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

    public function findById(string $id): ProcessCcInstance
    {
        AssertHelper::notNull($param->id ?? '', '参数ID不能为空');
        return $this->get($id);
    }

    public function page($param): array
    {
        $map1 = [
            ['pci.state', '=', $param->state],
            ['pci.actor_id', '=', $param->actor_id],
        ];
        if (!empty($param->name)) {
            $map1[] = ['pd.name', '=', $param->name];
        }
        if (!empty($param->display_name)) {
            $map1[] = ['pd.display_name', 'like', $param->display_name.'%'];
        }
        [$page, $limit] = PageParam::getPageValue($param);
        $processInstance   = ProcessInstance::getTableName();
        $processCcInstance = ProcessCcInstance::getTableName();
        $processDefine     = ProcessDefine::getTableName();

        $list  = Db::table($processCcInstance)
            ->alias('pci')
            ->where($map1)
            ->field('pi.*,pci.id,actor_id,pci.actor_id,pci.state as is_cc,pci.process_instance_id,pd.name,pd.display_name,pd.version')
            ->join([$processInstance => 'pi'], 'pci.process_instance_id = pi.id')
            ->join([$processDefine => 'pd'], 'pd.id = pi.process_define_id')
            ->order('pci.create_time', 'desc')
            ->page($page, $limit)
            ->select()
            ->toArray();
        $count = Db::table($processCcInstance)
            ->alias('pci')
            ->where($map1)
            ->join([$processInstance => 'pi'], 'pci.process_instance_id = pi.id')
            ->join([$processDefine => 'pd'], 'pd.id = pi.process_define_id')
            ->count();

        foreach ($list as $key => $value) {
            $list[$key]['create_time'] = DateTimeHelper::timestampToString($value['create_time']);
            $list[$key]['ext']         = json_decode($value['variable']);
            if (!empty($value['variable'])) {
                //提前f_前缀的表单数据
                $list[$key]['form_data'] = ProcessFlowUtils::filterObjectByPrefix(json_decode($value['variable'], false), 'f_');
            }
        }
        return compact('list', 'count');
    }
}
