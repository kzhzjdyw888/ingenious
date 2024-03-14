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

namespace ingenious\model;

use ingenious\core\Execution;
use ingenious\core\ServiceContext;
use ingenious\enums\err\LfErrEnum;
use ingenious\ex\LFlowException;
use ingenious\libs\traits\DynamicMethodTrait;
use ingenious\libs\utils\AssertHelper;
use ingenious\libs\utils\ExpressionUtil;
use ingenious\libs\utils\ReflectUtil;

class DecisionModel extends NodeModel
{

    use DynamicMethodTrait;

    private string|null $expr; // 决策表达式
    private string|null $handleClass; // 决策处理类

    public function exec(Execution $execution)
    {
        $isFound      = false;
        $nextNodeName = null;

        //判断节点处理
        if (isset($this->expr) && !empty($this->expr)) {
            $obj          = ExpressionUtil::eval($this->expr, $execution->getArgs());
            $nextNodeName = (string)$obj;
        } else if (isset($this->handleClass) && !empty($this->handleClass)) {
            $decisionHandler = ServiceContext::find($this->handleClass);
            $nextNodeName    = $decisionHandler->decide($execution);
        }

        //边线表达式处理
        foreach ($this->outputs as $transitionModel) {
            if ($transitionModel->getExpr() !== null && (bool)ExpressionUtil::eval($transitionModel->getExpr(), $execution->getArgs())) {
                //边线表达式跳转
                $isFound = true;
                $transitionModel->setEnabled(true);
                $transitionModel->execute($execution);
            } else if ($transitionModel->getTo() === $nextNodeName) {
                //判断节点表达式类处理器跳转
                $isFound = true;
                $transitionModel->setEnabled(true);
                $transitionModel->execute($execution);
            }
        }

        if (!$isFound) {
            throw new LFlowException('Not found next node', LfErrEnum::NOT_FOUND_NEXT_NODE[1]);
        }
    }
}
