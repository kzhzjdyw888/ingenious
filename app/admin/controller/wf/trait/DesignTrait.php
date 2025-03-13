<?php

namespace app\admin\controller\wf\trait;


trait DesignTrait
{

    /**
     * start
     *
     * @return string
     */
    public function start(): string
    {
        return $this->fetch('wf/common/design/panel/start');
    }

    /**
     * decision
     *
     * @return string
     */
    public function decision(): string
    {
        return $this->fetch('wf/common/design/panel/decision');
    }

    /**
     * task
     *
     * @return string
     */
    public function task(): string
    {
        return $this->fetch('wf/common/design/panel/task');
    }

    /**
     * custom
     *
     * @return string
     */
    public function custom(): string
    {
        return $this->fetch('wf/common/design/panel/custom');
    }

    /**
     * process
     *
     * @return string
     */
    public function process(): string
    {
        return $this->fetch('wf/common/design/panel/process');
    }

    /**
     * fork
     *
     *
     * @return string
     * @throws \Throwable
     */
    public function fork(): string
    {
        return $this->fetch('wf/common/design/panel/fork');
    }

    /**
     * join
     *
     *
     * @return string
     * @throws \Throwable
     */
    public function join(): string
    {
        return $this->fetch('wf/common/design/panel/join');
    }

    /**
     * subProcess
     *
     *
     * @return string
     * @throws \Throwable
     */
    public function subProcess(): string
    {
        return $this->fetch('wf/common/design/panel/subProcess');
    }

    /**
     * wfSubProcess
     *
     *
     * @return string
     * @throws \Throwable
     */
    public function wfSubProcess(): string
    {
        return $this->fetch('wf/common/design/panel/wfSubProcess');
    }

    /**
     * end
     *
     *
     * @return string
     * @throws \Throwable
     */
    public function end(): string
    {
        return $this->fetch('wf/common/design/panel/end');
    }

    /**
     * transition
     *
     *
     * @return string
     * @throws \Throwable
     */
    public function transition(): string
    {
        return $this->fetch('wf/common/design/panel/transition');
    }

    /**
     * detail
     *
     *
     * @return string
     * @throws \Throwable
     */
    public function detail(): string
    {
        return $this->fetch('wf/common/design/panel/detail');
    }

    /**
     * import
     *
     *
     * @return string
     * @throws \Throwable
     */
    public function import(): string
    {
        return $this->fetch('wf/common/design/panel/import');
    }
}
