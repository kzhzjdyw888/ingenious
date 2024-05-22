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
