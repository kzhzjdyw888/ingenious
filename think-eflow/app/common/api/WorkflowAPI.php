<?php

namespace app\common\api;

use app\common\enums\ProcessArgsConst;
use Exception;
use madong\ingenious\core\ProcessEngines;
use madong\ingenious\core\ServiceContext;
use madong\ingenious\enums\ProcessConstEnum;
use madong\ingenious\enums\ProcessSubmitTypeEnum;
use madong\ingenious\interface\IProcessEngines;
use madong\ingenious\interface\services\IProcessCcInstanceService;
use madong\ingenious\interface\services\IProcessDefineFavoriteService;
use madong\ingenious\interface\services\IProcessDefineService;
use madong\ingenious\interface\services\IProcessDesignHistoryService;
use madong\ingenious\interface\services\IProcessDesignService;
use madong\ingenious\interface\services\IProcessFormHistoryService;
use madong\ingenious\interface\services\IProcessFormService;
use madong\ingenious\interface\services\IProcessInstanceHistoryService;
use madong\ingenious\interface\services\IProcessInstanceService;
use madong\ingenious\interface\services\IProcessSurrogateService;
use madong\ingenious\interface\services\IProcessTaskActorHistoryService;
use madong\ingenious\interface\services\IProcessTaskActorService;
use madong\ingenious\interface\services\IProcessTaskHistoryService;
use madong\ingenious\interface\services\IProcessTaskService;
use madong\ingenious\interface\services\IProcessTypeService;
use madong\ingenious\libs\utils\AssertHelper;
use madong\ingenious\libs\utils\ProcessFlowUtils;
use madong\think\wf\basic\BaseService;
use think\facade\Db;

class WorkflowAPI extends BaseService
{
    public array $configuration = [];

    public ?IProcessEngines $engine;

    private array $strategyMapping = [
        'carbon'          => IProcessCcInstanceService::class,
        'define'          => IProcessDefineService::class,
        'define_fav'      => IProcessDefineFavoriteService::class,
        'design'          => IProcessDesignService::class,
        'design_hist'     => IProcessDesignHistoryService::class,
        'form'            => IProcessFormService::class,
        'form_hist'       => IProcessFormHistoryService::class,
        'instance'        => IProcessInstanceService::class,
        'instance_hist'   => IProcessInstanceHistoryService::class,
        'surrogate'       => IProcessSurrogateService::class,
        'task'            => IProcessTaskService::class,
        'task_hist'       => IProcessTaskHistoryService::class,
        'task_actor'      => IProcessTaskActorService::class,
        'task_actor_hist' => IProcessTaskActorHistoryService::class,
        'category'        => IProcessTypeService::class,
    ];

    public function __construct()
    {
        $this->configuration = config('engine', []);
        $this->engine        = new ProcessEngines($this->configuration ?? []);
    }

    /**
     * 发起申请
     *
     * @param object $param
     *
     * @return array
     * @throws Exception
     */
    public function startAndExecute(object $param): array
    {
        return $this->transaction(function () use ($param) {
            try {
                //1.0 参数转换字典
                $args = ProcessFlowUtils::variableToDict($param);
                $args->put(ProcessConstEnum::USER_USER_ID->value, getCurrentUser());
                AssertHelper::notNull($args->get(ProcessConstEnum::PROCESS_DEFINE_ID_KEY->value, null), '[' . ProcessConstEnum::PROCESS_DEFINE_ID_KEY->value . 'field] - this argument is required; it must not be null');

                // 2.1验证是否内置表单
                $processDefine = $this->client('define.findById', $args->get(ProcessConstEnum::PROCESS_DEFINE_ID_KEY->value));
                if (empty($processDefine)) {
                    throw new Exception(ProcessConstEnum::PROCESS_DEFINE_ID_KEY->value + '流程定义不存在');
                }
                $graph_data   = (array)$processDefine->content;
                $instanceUrl  = $graph_data['instance_url'] ?? '';
                $instanceType = $graph_data['instance_type'] ?? 1;
                //2.2获取所有的表单数据可以优化到流程定义里面
                $builtForm = config('form',[]);
                $currForm  = [];
                foreach ($builtForm as $item) {
                    if ($item['value'] === $instanceUrl) {
                        $currForm = $item;
                        break;
                    }
                }

                //2.3 如果有内置表单并且设定数据表创建数据库数据
                if (!empty($currForm) && !empty($currForm['table'])) {
                    $formData = filterByPrefix($param);
                    //兼容引擎表单传入的单据编号不带f_前缀单独处理
                    $businessNo = $args->get('business_no', '');
                    if (empty($businessNo)) {
                        throw new \Exception('单据编号不能为空');
                    }
                    $table      = $currForm['table'];
                    $fields     = $currForm['field'];
                    $primaryKey = 'id';
                    $data       = ensureKeys($fields, $formData, [$primaryKey]);
                    //是否包含创建时间有追加当前时间
                    if (in_array('create_time', $fields)) {
                        $data['create_time'] = time();
                    }
                    $data['business_no'] = $businessNo;
                    DB::table($table)->insert($data);
                    //添加配置数据到引擎后续逻辑处理可以优化到流程设计器流程里面
                    $args->put(ProcessArgsConst::IS_INTERNAL_FORM, true);//是否内置表单
                    $args->put(ProcessArgsConst::TABLE_NAME, $table);//数据库名称
                    $args->put(ProcessArgsConst::TABLE_FIELD, $fields);//数据库字段
                }
                //3.0 发起流程调用工作流引擎
                $result = $this->engine->processInstanceService()->startAndExecute($args->get(ProcessConstEnum::PROCESS_DEFINE_ID_KEY->value), $args);
                return [$result->getData($result->getPk())];
            } catch (\Throwable $e) {
                throw new Exception($e->getMessage());
            }
        });
    }

    /**
     * 执行任务
     *
     * @param object $param
     *
     * @return mixed
     * @throws Exception
     */
    public function execute(object $param): bool
    {
        return $this->transaction(function () use ($param) {
            try {
                $args          = ProcessFlowUtils::variableToDict($param);
                $scene         = $args->get(ProcessConstEnum::SUBMIT_TYPE->value);
                $processTaskId = $args->get(ProcessConstEnum::PROCESS_TASK_ID_KEY->value);
                $operator      = $args->get(ProcessConstEnum::OPERATOR_KEY->value);
                switch ($scene) {
                    case ProcessSubmitTypeEnum::AGREE->value:
                        //同意申请
                        $this->engine->executeProcessTask($processTaskId, $operator, $args);
                        break;
                    case ProcessSubmitTypeEnum::REJECT->value:
                        //拒绝申请
                        $this->engine->executeAndJumpToEnd($processTaskId, $operator, $args);
                        break;
                    case ProcessSubmitTypeEnum::ROLLBACK->value:
                        //退回上一步
                        $this->engine->executeAndJumpTask($processTaskId, $operator, $args);
                        break;
                    case ProcessSubmitTypeEnum::JUMP->value:
                        // 跳转到指定节点
                        $taskName = $args->get(ProcessConstEnum::TASK_NAME->value);
                        $this->engine->executeAndJumpTask($processTaskId, $operator, $args, $taskName);
                        break;
                    case ProcessSubmitTypeEnum::ROLLBACK_TO_OPERATOR->value:
                        //退回申请人
                        $this->engine->executeAndJumpToFirstTaskNode($processTaskId, $operator, $args);
                        break;
                    case ProcessSubmitTypeEnum::COUNTERSIGN_DISAGREE->value:
                        //会签不同意 追加不同意标识
                        $args->put(ProcessConstEnum::COUNTERSIGN_DISAGREE_FLAG->value, 1);
                        $this->engine->executeProcessTask($processTaskId, $operator, $args);
                        break;
                    default:
                        //默认执行
                        $this->engine->executeProcessTask($processTaskId, $operator, $args);
                        break;
                }

                // 存在抄送
                $ccUserIds = $args->get(ProcessConstEnum::CC_ACTORS->value);
                if (!empty($ccUserIds)) {
                    $processInstanceId = $args->get(ProcessConstEnum::PROCESS_INSTANCE_ID_KEY->value);
                    if (is_array($ccUserIds)) {
                        $ccUserIds = implode(',', $ccUserIds);
                    }
                    //创建抄送列表
                    $this->engine->processInstanceService()->createCCInstance($processInstanceId, $operator, $ccUserIds);
                }
                return true;
            } catch (\Throwable $e) {
                throw new Exception($e->getMessage());
            }
        });
    }

    /**
     * 批量同意任务
     *
     * @param object $param
     *
     * @return array
     * @throws \Exception
     */
    public function approve(object $param): array
    {
        try {
            $data = $param->task_ids ?? [];
            unset($param->task_ids);
            $result = [];
            foreach ($data as $id) {
                try {
                    $param->process_task_id = $id;
                    $come                   = $this->execute($param);
                    if (empty($come)) {
                        $result[] = [
                            'task_id' => $id,
                            'success' => false,
                            'error'   => '执行失败',
                            'result'  => 'Failed to execute task_id: ' . $id,
                        ];
                    }

                    $result[] = [
                        'task_id' => $id,
                        'success' => true,
                        'error'   => '',
                        'result'  => 'Result for task_id: ' . $id,
                    ];

                } catch (\Throwable $e) {
                    $result[] = [
                        'task_id' => $id,
                        'success' => false,
                        'error'   => $e->getMessage(),
                        'result'  => $e->getMessage() . 'task_id: ' . $id,
                    ];
                }
            }
            return $result ?? [];
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 调用指定的 API 并处理参数。
     *
     * @param string $api    API 名称，格式为 'strategyKey.methodName'
     * @param mixed  $params 方法的参数
     *
     * @return mixed 方法返回值
     * @throws \Exception
     */
    public function client(string $api, ...$params): mixed
    {
        try {
            return $this->executeStrategy($api, ...$params);
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param string $api       格式为 'strategyKey.methodName'
     * @param mixed  ...$params 方法的参数
     *
     * @return mixed 方法返回值
     * @throws Exception
     */
    private function executeStrategy(string $api, ...$params): mixed
    {
        try {
            list($strategyKey, $methodName) = explode('.', $api);
            // 确保映射中存在该策略
            if (!isset($this->strategyMapping[$strategyKey])) {
                throw new Exception("Strategy $strategyKey is not allowed");
            }
            // 获取实际的策略类名
            $className = $this->strategyMapping[$strategyKey];
            // 确保类存在
            if (!ServiceContext::exist($className)) {
                throw new Exception("Class $className does not exist");
            }
            $strategy = ServiceContext::find($className);
            // 确保类实现了接口
            if (!$strategy instanceof $className) {
                throw new Exception("Class $className must implement StrategyInterface");
            }
            // 确保方法存在
            if (!method_exists($strategy, $methodName)) {
                throw new Exception("Method $methodName does not exist in class $className");
            }
            return $strategy->$methodName(...$params);
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }
}
