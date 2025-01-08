<?php
/**
 *+------------------
 * ingenious
 *+------------------
 * Copyright (c) https://gitcode.com/motion-code  All rights reserved.
 *+------------------
 * Author: Mr. April (405784684@qq.com)
 *+------------------
 * Software Registration Number: 2024SR0694589
 * Official Website: https://madong.tech
 */

namespace madong\ingenious\model;

use madong\ingenious\ex\LFlowException;
use madong\ingenious\interface\IActionInterface;
use madong\ingenious\interface\IExecution;
use madong\ingenious\libs\traits\DynamicPropsTrait;
use madong\ingenious\libs\utils\InstanceFactory;
use madong\ingenious\libs\utils\Logger;
use ReflectionClass;

/**
 * @method getOutputs()
 * @method setDisplayName(string $param)
 * @method setName($getId)
 * @method setLayout(string $sprintf)
 * @method setPreInterceptors(mixed $param)
 * @method setPostInterceptors(mixed $param)
 * @method setOutputs($output)
 * @method setClazz(string $param)
 * @method setMethodName(string $param)
 * @method setArgs(string $param)
 * @method setVar(string $param)
 * @method setExpr(string $param)
 * @method setHandleClass(string $param)
 * @method setForm($getStr)
 * @method setAssignee($getStr)
 * @method setAssigneeFormKey(string $param)
 * @method setAssignmentHandler($getStr)
 * @method setGroupKey(string $param)
 * @method setTaskType($codeOf)
 * @method setPerformType($codeOf)
 * @method setReminderTime($getStr)
 * @method setReminderRepeat($getStr)
 * @method setExpireTime($getStr)
 * @method setAutoExecute($getStr)
 * @method setCallback($getStr)
 * @method setVersion($string)
 * @method setCandidateUsers($getStr)
 * @method setCandidateGroups($getStr)
 * @method setCandidateHandler($getStr)
 * @method setCountersignCompletionCondition($param)
 * @method setExt($ext)
 * @method setCountersignType($codeOf)
 * @method getInputs()
 */
abstract class NodeModel extends BaseModel implements IActionInterface
{

    use DynamicPropsTrait;

    protected string $layout = '';// 布局属性(x,y,w,h)
    // 输入边集合
    protected array $inputs = [];
    // 输出边集合
    protected array $outputs = [];
    protected string|null $preInterceptors; // 节点前置拦截器
    protected string|null $postInterceptors; // 节点后置拦截器

    /**
     * 由子类自定义执行方法
     * @param \madong\ingenious\interface\IExecution $execution
     *
     * @return mixed
     */
    abstract function exec(IExecution $execution);

    /**
     * @throws \ReflectionException
     */
    public function execute(IExecution $execution): void
    {
        // 0.设置当前节点模型
        $execution->setNodeModel($this);
        // 1. 调用前置拦截器
        $this->execPreInterceptors($execution);
        // 2. 调用子类的exec方法
        $this->exec($execution);
        // 3. 调用后置拦截器
        $this->execPostInterceptors($execution);
    }

    /**
     * 执行输出边
     *
     * @param \madong\ingenious\core\Execution $execution
     */
    protected function runOutTransition(IExecution $execution): void
    {

        foreach ($this->outputs as $Transition) {
            $Transition->setEnabled(true);
            $Transition->execute($execution);
        }
    }

    /**
     * 执行节点前置拦截器
     *
     * @param \madong\ingenious\core\Execution $execution
     *
     * @throws \ReflectionException
     */
    private function execPreInterceptors(IExecution $execution): void
    {
        if (empty($this->preInterceptors)) {
            $this->preInterceptors = $execution->getProcessModel()->getPreInterceptors();
        }
        $this->execInterceptors($this->preInterceptors, $execution);
    }

    /**
     * 执行后置拦截器
     *
     * @param \madong\ingenious\core\Execution $execution
     *
     * @throws \ReflectionException
     */
    private function execPostInterceptors(IExecution $execution): void
    {
        if (empty($this->postInterceptors)) {
            $this->postInterceptors = $execution->getProcessModel()->getPostInterceptors();
        }
        $this->execInterceptors($this->postInterceptors, $execution);
    }

    /**
     * 执行节点拦截器
     *
     * @param string                                  $interceptors
     * @param \madong\ingenious\core\Execution $execution
     *
     * @throws \ReflectionException
     */
    private function execInterceptors(string $interceptors, IExecution $execution)
    {
        if (empty($interceptors)) {
            return;
        }
        // 存在多个，英文逗号分割
        $interceptorArr = explode(',', $interceptors);
        try {
            $instanceFactory = new InstanceFactory();
            foreach ($interceptorArr as $interceptor) {
                $newInstance = $instanceFactory->newInstance($interceptor);
                if (!empty($newInstance)) {
                    $newInstance->intercept($execution);
                }
            }
        } catch (LFlowException $e) {
            Logger::error("拦截器执行失败=" . json_encode($e->getMessage()));
            throw new LFlowException($e->getMessage());
        }
    }

    public function getNextModels(object $clazz): array
    {
        //记录已递归项，防止死循环
        $temp   = [];
        $models = [];
        foreach ($this->getOutputs() as $tm) {
            $this->addNextModels($models, $tm, $clazz, $temp);
        }
        return $models;
    }

    protected function addNextModels(array &$models, TransitionModel $tm, NodeModel|string $clazz, array &$temp): void
    {
        if (isset($temp[$tm->getTo()])) {
            return;
        }
        // 检查 clazz 参数的类型
        if (is_string($clazz)) {
            // clazz 是字符串，使用反射来创建类的实例
            try {
                $reflection = new ReflectionClass($clazz);
                $clazz      = $reflection->newInstance();
            } catch (\ReflectionException $e) {
                throw  new LFlowException($clazz . '未注册服务');
            }
        }
        if (is_a($tm->getTarget(), get_class($clazz))) {
            $models[] = $tm->getTarget();
        } else {
            foreach ($tm->getTarget()->getOutputs() as $tm2) {
                $temp[$tm->getTo()] = $tm->getTarget();
                $this->addNextModels($models, $tm2, $clazz, $temp);
            }
        }
    }

    /**
     * * 根据父节点模型、当前节点模型判断是否可退回。可退回条件：
     * 1、满足中间无fork、join、subprocess模型
     * 2、满足父节点模型如果为任务模型时，参与类型为any
     *
     * @param \madong\ingenious\model\NodeModel $current
     * @param \madong\ingenious\model\NodeModel $parent
     *
     * @return bool
     */
    public static function canRejected(NodeModel $current, NodeModel $parent): bool
    {
        $result = false;
        foreach ($current->getInputs() as $tm) {
            $source = $tm->getSource();
            if ($source === $parent) {
                return true;
            }
            if ($source instanceof ForkModel ||
                $source instanceof JoinModel ||
                // $source instanceof SubProcessModel ||
                $source instanceof StartModel) {
                continue;
            }
            $result = $result || self::canRejected($source, $parent);
        }
        return $result;
    }
}
