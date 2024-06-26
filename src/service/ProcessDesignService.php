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

use ingenious\db\ProcessDesign;
use ingenious\db\ProcessDesignHistory;
use ingenious\db\ProcessFormHistory;
use ingenious\db\ProcessType;
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
        if ($processDesign !== null) {
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
            $processDesignHistory   = (new ProcessDesignHistory())->where(['process_design_id' => $id])->order('create_time', 'desc')->find();
            $graph_data             = $processDesignHistory->content ?? (object)[];
            $processDesign->content = $graph_data;
        }
        return $processDesign;
    }

    public function updateDefine(object $jsonObject): bool
    {
        $processDesignFlow       = new ProcessDesignHistory();
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
        // 获取当前版本号并转换为浮点数
        $processDesignHistoryService = new ProcessDesignHistoryService();
        $newVersion                  = 1.0;//默认版本
        $history                     = $processDesignHistoryService->selectList(['process_design_id' => $data->process_design_id], 'versions', 0, 0, 'create_time asc', [], true)->last();
        if (!empty($history)) {
            $currentVersion = (float)$history->getData('versions');
            $newVersion     = round($currentVersion + 0.1, 1);
        }

        $data->versions = $newVersion;
        ModelUtils::copyProperties($data, $processDesignFlow);
        return $processDesignFlow->save();
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
            $content              = $processDesign->getData('content');
            if (!empty($content)) {
                AssertHelper::notNull('instance_url', '部署失败,缺少表单信息');
            }
            $processDefineService->deploy(ArrayHelper::arrayToObject($content), $operation);
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
            AssertHelper::notNull($processDefine, '该流程未部署,请先部署流程');
            $content = $processDesign->getData('content');
            if (!empty($content)) {
                AssertHelper::notNull('instance_url', '部署失败,缺少表单信息');
            }
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
