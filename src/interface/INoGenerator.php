<?php
/**
 * Copyright (C) 2024 Ingenstream
 * This software is licensed under the Apache-2.0 license.
 * A copy of the license can be found at http://www.apache.org/licenses/LICENSE-2.0
 * Official Website: http://www.ingenstream.cn
 * Author: Mr. April <405784684@qq.com>
 * Project: Ingenious
 * Repository: https://gitee.com/ingenstream/ingenious
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
