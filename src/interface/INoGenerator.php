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
declare (strict_types=1);

namespace ingenious\interface;

use ingenious\model\ProcessModel;

/**
 * 编号生成器接口
 * 流程实例的编号字段使用该接口实现类来产生对应的编号
 *
 * @author Mr.April
 * @since  1.0
 */
interface INoGenerator
{
    /**
     *  生成器方法
     *
     * @param \ingenious\model\ProcessModel $model
     *
     * @return string  编号
     */
    public function generate(ProcessModel $model): string;

}
