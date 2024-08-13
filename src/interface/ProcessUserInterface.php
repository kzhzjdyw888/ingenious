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
