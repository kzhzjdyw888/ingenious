<?php

namespace app\admin\controller\wf\trait;

use madong\ingenious\libs\utils\ArrayHelper;

trait TrackTrait
{

    /**
     * 轨迹视图
     *
     * @return string
     * @throws \Throwable
     */
    public function track(): string
    {
        return $this->fetch('wf/common/track/index');
    }

    /**
     * 流程图
     *
     * @return string
     * @throws \Throwable
     */
    public function trajectory(): string
    {
        $process_define_id   = input('get.process_define_id');//流程定义ID
        $process_instance_id = input('get.process_instance_id');//流程示例id

        $res       = $this->service->client('define.findById', $process_define_id);
        $ret       = $this->service->client('instance.highLight', $process_instance_id);
        $graphData = (object)[];
        $highLight = (object)[];
        if (!empty($res)) {
            $content = $res->getData('content');
            if (!empty($content)) {
                $graphData = is_array($content) ? ArrayHelper::arrayToObject($content) : $content;
            }
        }

        if (!empty($ret)) {
            $highLight = $ret;
        }

        $data = [
            'viewer'        => true,
            'graphData'     => $graphData,
            'highLight'     => $highLight,
            'commitPath'    => '',
            'defaultConfig' => (object)['grid' => true],
        ];
        return $this->fetch('wf/common/design/index', ['data' => json_encode($data)]);
    }

    /**
     * 时间线
     *
     * @return string
     * @throws \Throwable
     */
    public function timeline(): string
    {
        return $this->fetch('wf/common/track/template/timeline');
    }

    /**
     * 时间表
     *
     * @return string
     * @throws \Throwable
     */
    public function timetable(): string
    {
        return $this->fetch('wf/common/track/template/timetable');
    }

}
