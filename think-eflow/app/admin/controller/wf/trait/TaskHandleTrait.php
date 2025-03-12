<?php

namespace app\admin\controller\wf\trait;

trait TaskHandleTrait
{

    /**
     * 审核处理页面
     *
     * @return string
     * @throws \Throwable
     */
    public function handle(): string
    {
        return $this->fetch('wf/common/task/handle/index');
    }

    /**
     * 审核意见
     *
     * @return string
     * @throws \Throwable
     */
    public function handleApprove(): string
    {
        return $this->fetch('wf/common/task/handle/handleApprove');
    }

    /**
     * 任务详情
     *
     * @return string
     * @throws \Throwable
     */
    public function detail(): string
    {
        return $this->fetch('wf/common/task/detail/index');
    }

    /**
     * 任务详情-内置html表单
     *
     * @return string
     * @throws \Throwable
     */
    public function detail_idf(): string
    {
        $id          = input('id');
        $operate     = input('operate', 'add');
        $instanceUrl = input('instance_url');
        $userInfo    = getCurrentUser(true);
        return raw_view($instanceUrl, ['id' => $id, 'operate' => $operate, 'nickname' => $userInfo['nickname'] ?? '']);
    }

}
