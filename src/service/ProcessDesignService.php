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

use ingenious\db\ProcessDesign;
use ingenious\db\ProcessDesignHis;
use ingenious\db\ProcessType;
use ingenious\db\ProcessFormBuilder;
use ingenious\enums\ProcessConst;
use ingenious\libs\base\BaseService;
use ingenious\libs\utils\ArrayHelper;
use ingenious\libs\utils\AssertHelper;
use ingenious\libs\utils\ModelUtils;
use ingenious\libs\utils\PageParam;
use ingenious\service\interface\ProcessDesignServiceInterface;

/**
 * 流程设计 服务实现类
 *
 * @author Mr.April
 * @since  1.0
 */
class ProcessDesignService extends BaseService implements ProcessDesignServiceInterface
{

    /**
     * @inheritDoc
     */
    protected function setModel(): string
    {
        return ProcessDesign::class;
    }

    public function create(object $param): bool
    {
        unset($param->id);
        AssertHelper::notTrue($this->checkUniqueName(['name' => $param->name]), '唯一编码已存在，请检查name参数');
        $processDesign = new ProcessDesign();
        ModelUtils::copyProperties($param, $processDesign);
        return $processDesign->save();

    }

    /**
     * update
     *
     * @param object $param
     *
     * @return bool
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function update(object $param): bool
    {
        AssertHelper::notNull($param->id ?? '', '参数异常，请检查ID参数');
        AssertHelper::notNull($param->name ?? '', '参数异常，唯一编码不能为空');
        AssertHelper::notTrue($this->checkUniqueName(['notId' => $param->id, 'name' => $param->name]), '唯一编码已存在，请检查name参数');
        $processDesign = $this->get($param->id);
        AssertHelper::notNull($processDesign, '资源不存在');
        ModelUtils::copyProperties($param, $processDesign);
        return $processDesign->save();
    }

    public function del(string $id): bool
    {
        $processDesign = $this->get($id);
        if ($processDesign != null) {
            return $processDesign->deleteWithHistory();
        }
        return false;
    }

    public function page(object $param): array
    {
        $where = ArrayHelper::paramsFilter($param, [
            ['name', ''],
            ['display_name', ''],
            ['is_del', '0'],
        ]);
        [$page, $limit] = PageParam::getPageValue($param);
        $list = $this->selectList($where, '*', $page, $limit, 'create_time asc', ['processType'], true);
        foreach ($list as $item) {
            $item->type_name = $item->processType->name ?? '顶层';
        }
        $list  = $list->hidden(['processType'])->toArray();
        $count = $this->count($where);
        return compact('list', 'count');
    }

    public function findById(string $id): ?ProcessDesign
    {
        AssertHelper::notNull($id, '参数ID不能为空');
        $processDesign = $this->get($id);
        if ($processDesign !== null) {
            $processDesignHis       = (new ProcessDesignHis())->where(['process_design_id' => $id])->order('create_time', 'desc')->find();
            $processFormBuilder     = (new ProcessFormBuilder())->where(['process_design_id' => $id])->order('create_time', 'desc')->find();
            $processDesign->hid     = $processDesignHis->id ?? '';
            $processDesign->content = $processDesignHis->content ?? (object)[];
            $processDesign->fid     = $processFormBuilder->id ?? '';
            $processDesign->form_builder = $processFormBuilder->content ?? (object)[];
        }
        return $processDesign;
    }

    public function updateDefine(object $jsonObject): bool
    {
        $processDesignHis        = new ProcessDesignHis();
        $data                    = new \stdClass();
        $data->process_design_id = $jsonObject->{ProcessConst::PROCESS_DESIGN_ID_KEY};
        $data->create_user       = $jsonObject->{ProcessConst::CREATE_USER};
        $processDesign           = $this->get($data->process_design_id);
        $processDesign->set('update_time', time());
        $processDesign->set('update_user', $data->create_user);
        $processDesign->save();
        unset($jsonObject->{ProcessConst::CREATE_USER});
        unset($jsonObject->{ProcessConst::PROCESS_DESIGN_ID_KEY});
        $data->content = $jsonObject;
        ModelUtils::copyProperties($data, $processDesignHis);
        return $processDesignHis->save();
    }


    public function updateBuilder(object $jsonObject):bool
    {
            $processFormBuilder        = new ProcessFormBuilder();
            $data                    = new \stdClass();
            $data->process_design_id = $jsonObject->{ProcessConst::PROCESS_DESIGN_ID_KEY};
            $data->create_user       = $jsonObject->{ProcessConst::CREATE_USER};
            $processDesign           = $this->get($data->process_design_id);
            $processDesign->set('update_time', time());
            $processDesign->set('update_user', $data->create_user);
            $processDesign->save();
            unset($jsonObject->{ProcessConst::CREATE_USER});
            unset($jsonObject->{ProcessConst::PROCESS_DESIGN_ID_KEY});
            $data->content = $jsonObject;
            ModelUtils::copyProperties($data, $processFormBuilder);
            return $processFormBuilder->save();
    }


    public function deploy(string $processDesignId, string|int $operation): void
    {
        $processDesign = $this->findById($processDesignId);
        AssertHelper::notNull($processDesign, '部署失败，流程设计不存在或被删除');
        AssertHelper::notNull($processDesign, 'operation 不能为空');
        $processDesign->set('is_deployed', 1);
        $processDesign->set('update_user', $operation);
        if ($processDesign->save()) {
            // 先更新状态，更新成功，再部署
            $processDefineService = new  ProcessDefineService();
            $processDefineService->deploy(ArrayHelper::arrayToObject($processDesign->getData('content')), $operation);
        }
    }

    public function redeploy(string $processDesignId, string|int $operation): void
    {
        $processDesign = $this->findById($processDesignId);
        $processDesign->set('is_deployed', 1);
        $processDesign->set('update_user', $operation);
        if ($processDesign->save()) {
            // 先更新状态，更新成功，再部署
            $processDefineService = new  ProcessDefineService();
            $processDefine        = $processDefineService->selectList(['name' => $processDesign->getData('name')], '*', 0, 0, 'version', [], true)->last();
            $processDefineService->redeploy($processDefine->getData('id'), ArrayHelper::arrayToObject($processDesign->getData('content')), $operation);
        }
    }

    public function listByType(): ?ProcessType
    {

    }

    private function checkUniqueName(array $where): bool
    {
        $result = $this->selectList($where, '*', 0, 0, '', [], true)->toArray();
        return !empty($result) ?? false;
    }
}
