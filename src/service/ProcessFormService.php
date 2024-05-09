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

use ingenious\db\ProcessForm;
use ingenious\db\ProcessFormHistory;
use ingenious\db\ProcessType;
use ingenious\enums\ProcessConst;
use ingenious\libs\base\BaseService;
use ingenious\libs\utils\ArrayHelper;
use ingenious\libs\utils\AssertHelper;
use ingenious\libs\utils\ModelUtils;
use ingenious\libs\utils\PageParam;
use ingenious\service\interface\ProcessFormServiceInterface;

/**
 * 表单设计 服务实现类
 *
 * @author Mr.April
 * @since  1.0
 */
class ProcessFormService extends BaseService implements ProcessFormServiceInterface
{

    /**
     * @inheritDoc
     */
    protected function setModel(): string
    {
        return ProcessForm::class;
    }

    public function create(object $param): bool
    {
        unset($param->id);
        AssertHelper::notTrue($this->checkUniqueName(['name' => $param->name]), '唯一编码已存在，请检查name参数');
        $processDesign = new ProcessForm();
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
        $processForm = $this->get($id);
        if ($processForm != null) {
            return $processForm->deleteWithHistory();
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

    public function findById(string $id): ?ProcessForm
    {
        AssertHelper::notNull($id, '参数ID不能为空');
        $processForm = $this->get($id);
        if ($processForm !== null) {
            $processFormHistory    = (new ProcessFormHistory())->where(['process_form_id' => $id])->order('create_time', 'desc')->find();
            $processForm->form     = (object)[];
            $processForm->versions = (float)1.0;
            if ($processFormHistory != null) {
                $processForm->form     = $processFormHistory->getData('content');
                $processForm->versions = $processFormHistory->getData('versions');
            }
        }
        return $processForm;
    }

    public function findByName(string $name): ?ProcessForm
    {
        AssertHelper::notNull($name, '参数name不能为空');
        $processForm = $this->get(['name' => $name]);
        if ($processForm !== null) {
            $processFormHistory    = (new ProcessFormHistory())->where(['process_form_id' => $processForm->getData('id')])->order('create_time', 'desc')->find();
            $processForm->form     = (object)[];
            $processForm->versions = (float)1.0;
            if ($processFormHistory != null) {
                $processForm->form     = $processFormHistory->getData('content');
                $processForm->versions = $processFormHistory->getData('versions');
            }
        }
        return $processForm;
    }

    public function updateForm(object $jsonObject): bool
    {
        $processFormHistory    = new ProcessFormHistory();
        $data                  = new \stdClass();
        $data->process_form_id = $jsonObject->{ProcessConst::PROCESS_FORM_ID_KEY};
        $data->create_user     = $jsonObject->{ProcessConst::CREATE_USER};
        $processDesign         = $this->get($data->process_form_id);
        $processDesign->set('update_time', time());
        $processDesign->set('update_user', $data->create_user);
        $processDesign->save();
        unset($jsonObject->{ProcessConst::CREATE_USER});
        unset($jsonObject->{ProcessConst::PROCESS_FORM_ID_KEY});
        $data->content = $jsonObject;
        // 获取当前版本号并转换为浮点数
        $processFormHistoryService = new ProcessFormHistoryService();
        $newVersion                = 1.0;//默认版本
        $history                   = $processFormHistoryService->selectList(['process_form_id' => $data->process_form_id], 'versions', 0, 0, 'create_time asc', [], true)->last();
        if (!empty($history)) {
            $currentVersion = (float)$history->getData('versions');
            $newVersion     = round($currentVersion + 0.1, 1);
        }
        $data->versions = $newVersion;
        ModelUtils::copyProperties($data, $processFormHistory);
        return $processFormHistory->save();
    }

    private function checkUniqueName(array $where): bool
    {
        $result = $this->selectList($where, '*', 0, 0, '', [], true)->toArray();
        return !empty($result) ?? false;
    }
}
