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

namespace ingenious\service\interface;

/**
 * 流程实例抄送-服务类
 *
 * @author Mr.April
 * @since  1.0
 */
interface ProcessCcInstanceServiceInterface
{
    /**
     * 添加流程实例抄送
     *
     * @param param
     *
     * @return
     */
    public function save($param):bool;

    /**
     * 更新流程实例抄送
     *
     * @param param
     *
     * @return
     */
    public function update($param):bool;

    /**
     * 通过id查询
     *
     * @param string $id
     *
     * @return
     */
    public function findById(string $id);

    /**
     * 自定义分页查询流程实例抄送
     * @param $param
     *
     * @return mixed
     */
    public function page($param):array;

}
