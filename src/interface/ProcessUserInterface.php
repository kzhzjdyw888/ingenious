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

namespace ingenious\interface;

use ingenious\libs\utils\Dict;

/**
 * 工作流用户信息API
 *
 * @author Mr.April
 * @since  1.0
 */
interface ProcessUserInterface
{

    /**
     * @param string|int $id
     *
     * @return object|null {user_name,user_id,real_name,dept_name,post_name,dept_id,post_id}
     */
    public function findUser(string|int $id): ?object;

}
