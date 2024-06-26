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

use ingenious\db\ProcessDefine;
use ingenious\enums\ProcessDefineStateEnum;
use ingenious\libs\base\BaseService;
use ingenious\libs\utils\ArrayHelper;
use ingenious\libs\utils\AssertHelper;
use ingenious\libs\utils\ModelUtils;
use ingenious\libs\utils\PageParam;
use ingenious\libs\utils\RedisCache;
use ingenious\model\ProcessModel;
use ingenious\parser\ModelParser;
use ingenious\service\interface\ProcessDefineServiceInterface;

/**
 * 流程定义-服务实现类
 *
 * @author Mr.April
 * @since  1.0
 */
class ProcessDefineService extends BaseService implements ProcessDefineServiceInterface
{

    protected function setModel(): string
    {
        return ProcessDefine::class;
    }

    public function create(object $param): bool
    {
        unset($param->id);
        $processDefine = new ProcessDefine();
        ModelUtils::copyProperties($param, $processDefine);
        return $processDefine->save();
    }

    public function update(object $param): bool
    {
        AssertHelper::notNull($param->id ?? '', '参数ID不能为空');
        $processDefine = $this->get($param->id);
        ModelUtils::copyProperties((object)$param, $processDefine);
        return $processDefine->save();
    }

    public function del(string $processDefineId): bool
    {
        $processDefine = $this->get($processDefineId);
        AssertHelper::notNull($processDefine, '删除失败找不到资源');
        return $processDefine->delete();
    }

    public function page(object $param): array
    {
        $where = ArrayHelper::paramsFilter($param, [
            ['name', ''],
            ['display_name', ''],
            ['type_id', ''],
            ['state', 1],
            ['version', ''],
            ['is_del', 0],
        ]);
        [$page, $limit] = PageParam::getPageValue($param);
        $list  = $this->selectList($where, '*', $page, $limit, 'name desc,version desc', [], true)->toArray();
        $count = $this->count($where);
        return compact('list', 'count');
    }

    public function findById(string $id): ?ProcessDefine
    {
        AssertHelper::notNull($id, '参数process_define_id不能为空');
        $processDefine = $this->get($id);
        AssertHelper::notNull($processDefine, '流程定义不存在或被删除');
        if (!empty($processDefine->getData('content'))) {
            $graph_data = $processDefine->getData('content');
            //使用表单key获取表单JSON数据
            $processDefine->set('form', (object)[]);
            if (isset($graph_data->instance_url) && !empty($graph_data->instance_url)) {
                $processFormService = new ProcessFormService();
                $processForm        = $processFormService->findByName($graph_data->instance_url);
                if (!empty($processForm)) {
                    $processDefine->set('form', $processForm->getData('form'));
                }
            }
        }
        return $processDefine;
    }

    public function deploy($param, string $operation): string
    {
        // 1. json定义文件转成流程模型
        $processModel = ModelParser::parse($param);
        // 2. 根据名称查询，取最新版本的流程定义记录
        $map1              = [
            'name'   => $param->name,
            'is_del' => 0,
        ];
        $processDesign     = new ProcessDesignService();
        $processDesignData = $processDesign->get($map1);
        AssertHelper::notNull($processDesignData, '请先定义流程');
        $processDesignDataHis = $processDesignData->history()->order('create_time', 'desc')->find();
        AssertHelper::notNull($processDesignDataHis, '请先定义流程');
        $processDefine = $this->selectList($map1, '*', 0, 0, 'version', [], true)->last();
        if ($processDefine !== null) {
            // 3.1 如果存在，则版本+1，并插入一条新的流程定义记录
            $processDefine->set('id', null);
            $processDefine->set('version', $processDefine->version + 1);
        } else {
            // 3.2 如果不存在，则版本默认为1，并插入一条新的流程定义记录
            $processDefine = new ProcessDefine();
            $processDefine->set('version', 1);
        }
        $processDefine->set('name', $processModel->getName());
        $processDefine->set('display_name', $processModel->getDisplayName());
        $processDefine->set('type_id', $param->type_id ?? '');
        $processDefine->set('create_time', time());
        $processDefine->set('update_time', time());
        $processDefine->set('create_user', $operation);
        $processDefine->set('state', ProcessDefineStateEnum::getCode(ProcessDefineStateEnum::ENABLE[1]));
        $processDefine->set('content', $param);
        $result = $processDefine::create($processDefine->toArray());
        return $result->getData('id');
    }

    public function redeploy(string $processDefineId, object $inputStream, string|int $operation): void
    {
        $processModel  = ModelParser::parse($inputStream);
        $processDefine = $this->get($processDefineId);
        $processDefine->set('name', $processModel->getName());
        $processDefine->set('display_name', $processModel->getDisplayName());
        $processDefine->set('type_id', $param->type_id ?? '');
        $processDefine->set('update_time', time());
        $processDefine->set('update_user', $operation);
        $processDefine->set('content', $inputStream);
        $processDefine->save();
    }

    public function unDeploy(string $processDefineId, string|int $operation): void
    {
        $processDefine = $this->get($processDefineId);
        $processDefine->set('state', ProcessDefineStateEnum::DISABLE[0]);
        $processDefine->set('update_user', $operation);
        $processDefine->save();
    }

    public function updateType(string $processDefineId, string $type, string|int $operation): void
    {
        AssertHelper::notEmpty($processDefineId, '参数 processDefineId 不能为空');
        $processDefine = $this->get($processDefineId);
        AssertHelper::notNull($processDefineId, '流程定义不存在或被删除');
        $processDefine->type_id = $type;
        $processDefine->save();
    }

    public function updateState(string $processDefineId, string|int $state, string|int $operation): void
    {
        AssertHelper::notEmpty($processDefineId, '参数 processDefineId 不能为空');
        $processDefine = $this->get($processDefineId);
        AssertHelper::notNull($processDefineId, '流程定义不存在或被删除');
        $processDefine->state = $state;
        $processDefine->save();
    }

    public function getById(string $processDefineId): ?ProcessDefine
    {
        AssertHelper::notEmpty($processDefineId, '参数 processDefineId 不能为空');
        return $this->get($processDefineId);
    }

    public function getProcessModel(string $processDefineId): ?ProcessModel
    {
        AssertHelper::notEmpty($processDefineId, '参数 processDefineId 不能为空');
        //如果缓存有则使用缓存
        return RedisCache::getCached(md5($processDefineId), null, 3600, function () use ($processDefineId) {
            return $this->processDefineToModel($this->get($processDefineId));
        });
    }

    public function processDefineToModel(ProcessDefine $processDefine): ?ProcessModel
    {
        if (empty($processDefine)) return null;
        $content = $processDefine->getData('content');
        if (empty($content)) return null;
        return ModelParser::parse($content);
    }

    public function getDefineJsonStr(string $processDefineId): string|null
    {
        AssertHelper::notEmpty($processDefineId, '参数ID不能为空');
        $processDefine = $this->get($processDefineId);
        if (empty($processDefine)) {
            return json_encode((object)[]);
        }
        $content = $processDefine->getData('content');
        return json_encode(ArrayHelper::arrayToObject($content));
    }

    public function getDefineJsonObject(string $processDefineId): \stdClass|string|bool
    {
        AssertHelper::notEmpty($processDefineId, '参数ID不能为空');
        $processDefine = $this->get($processDefineId);
        if (empty($processDefine)) {
            return json_encode((object)[]);
        }
        $content = $processDefine->getData('content') ?? (object)[];
        return ArrayHelper::arrayToObject($content);
    }

    public function upAndDown($param): void
    {
        if ($param->is_open === true) {
            foreach (explode(',', $param->ids ?? '') as $id) {
                $processDefine = (new ProcessDefine())->where(['id' => $id])->find();
                if (empty($processDefine)) {
                    continue;
                }
                $processDefine->set('state', ProcessDefineStateEnum::DISABLE[0]);
                $processDefine->set('update_user', $param->operation);
                $processDefine->save();
            }
        } else {
            foreach (explode(',', $param->ids ?? '') as $id) {
                $this->unDeploy($id, $param->operation);
            }
        }
    }

    public function getLastByName(string $name): ?ProcessDefine
    {
        return $this->selectList(['name' => $name, 'is_del' => 0], '*', 0, 0, 'version', [], true)->last();
    }

    public function getProcessDefineByVersion(string $name, int $version): ?ProcessDefine
    {
        return $this->selectList(['name' => $name, 'version' => $version, 'is_del' => 0], '*', 0, 0, '', [], true)->last();
    }

    public function favoritePage(object $param): array
    {
        $processDefineFavoriteService = new ProcessDefineFavoriteService();
        return $processDefineFavoriteService->page($param);
    }

    public function definitionFavorite(object $param): bool
    {
        $processDefineFavoriteService = new ProcessDefineFavoriteService();
        AssertHelper::notNull($param->user_id, '用户ID不能为空');
        AssertHelper::notNull($param->process_define_id, '流程定义ID不能为空');
        $map1     = [
            'user_id'           => $param->user_id,
            'process_define_id' => $param->process_define_id,
        ];
        $favorite = $processDefineFavoriteService->get($map1);
        if ($favorite == null) {
            return $processDefineFavoriteService->create($param);
        } else {
            if ($favorite->delete()) {
                //如果是取消收藏不用再次创建了
                if (empty($param->favorite) || $param->favorite == 0 || $param->favorite == '0') {
                    return true;
                }
                return $processDefineFavoriteService->create($param);
            }
        }
        return false;
    }

    public function favoriteDel(string|int|array $param): bool
    {
        $processDefineFavoriteService = new ProcessDefineFavoriteService();
        return $processDefineFavoriteService->del($param);
    }
}
