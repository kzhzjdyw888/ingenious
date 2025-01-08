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



/**
 * 工作流用户信息API
 *
 * @author Mr.April
 * @since  1.0
 */
interface IProcessUser
{

    /**
     * @param string|int $id
     *
     * @return object|null {user_name,user_id,real_name,dept_name,post_name,dept_id,post_id}
     */
    public function findUser(string|int $id): ?object;

}
