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


namespace madong\ingenious\interface;


use madong\ingenious\model\ProcessModel;

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
     * @param \madong\ingenious\model\ProcessModel $model
     *
     * @return string  编号
     */
    public function generate(ProcessModel $model): string;

}
