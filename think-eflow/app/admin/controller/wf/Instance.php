<?php

namespace app\admin\controller\wf;

use app\admin\controller\Base;
use app\admin\controller\wf\trait\TrackTrait;
use app\common\api\WorkflowAPI;
use app\common\util\Json;
use madong\ingenious\libs\utils\ArrayHelper;
use think\App;

/**
 * 实例
 *
 * @author Mr.April
 * @since  1.0
 */
class Instance extends Base
{

    use TrackTrait;

    protected array $middleware = ['AdminCheck', 'AdminPermission'];

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->service = new WorkflowAPI();
    }

    /**
     * 浏览
     *
     * @return string
     * @throws \Throwable
     */
    public function index(): string
    {
        return $this->fetch('wf/instance/index');
    }

    /**
     * 获取列表
     *
     * @return \think\Response
     * @throws \Exception
     */
    public function select(): \think\Response
    {
        $format          = input('format', 'normal');
        $methods         = [
            'select'     => 'formatSelect',
            'tree'       => 'formatTree',
            'table_tree' => 'formatTableTree',
            'normal'     => 'formatNormal',
        ];
        $format_function = $methods[$format] ?? 'formatNormal';
        $result          = $this->service->client('instance.list', (object)$this->request->all());
        return call_user_func([$this, $format_function], $result['items'], $result['total']);
    }

    /**
     * 详情
     *
     * @return \think\Response
     * @throws \Exception
     */
    public function show(): \think\Response
    {
        $id     = input('get.id');
        $result = $this->service->client('instance.findById', $id);
        $data   = [];
        if (!empty($result)) {
            $data    = $result->toArray();
            $content = $data['define']['content'] ? ArrayHelper::jsonToArray($data['define']['content']) : [];
            $type    = $content['instance_type'] ?? 2;
            $name    = $content['instance_url'] ?? '';
            if ($type == 1) {
                $result = $this->service->client('form.findByName', $name);
                if (!empty($result)) {
                    $form         = $result->toArray();
                    $data['form'] = $form['latest_history']['content'] ?? [];
                }
            }
        }
        return $data
            ? Json::success('ok', $data)
            : Json::fail('Resource not found');
    }

    /**
     * 高亮数据
     *
     * @return \think\Response
     */
    public function highLightData(): \think\Response
    {

        try {
            $id     = input('id', null);
            $result = $this->service->client('instance.highLight', $id);
            return Json::success('ok', $result);
        } catch (\Throwable $e) {
            return Json::fail($e->getMessage());
        }
    }

    /**
     * 获取审核记录列表
     *
     * @return \think\Response
     */
    public function approvalRecord(): \think\Response
    {
        try {
            $id     = input('id');
            $result = $this->service->client('instance.approvalRecord', $id);
            return Json::success('ok', $result);
        } catch (\Throwable $e) {
            return Json::fail($e->getMessage());
        }
    }

    /**
     * 撤回申请
     *
     * @return \think\Response
     */
    public function withdraw(): \think\Response
    {
        try {
            $data = ArrayHelper::normalize(input('id', []));
            $this->service->client('instance.withdraw', $data, getCurrentUser());
            return Json::success('ok');
        } catch (\Throwable $e) {
            return Json::fail($e->getMessage());
        }
    }

    /**
     * 删除
     *
     * @return \think\Response
     */
    public function delete(): \think\Response
    {
        try {
            $id   = input('id', null);
            $data = $id !== null && $id !== '0' ? $id : input('data', null);
            if ($data === null) {
                throw new \Exception('参数错误：缺少必要的参数（id 或 data）');
            }
            $result = $this->service->client('instance.cascadeDelete', $data);
            return Json::success('ok', $result);
        } catch (\Throwable $e) {
            return Json::fail($e->getMessage());
        }
    }

    /**
     * 实例详情
     *
     * @return \think\Response|string
     */
    public function detail(): \think\Response|string
    {
        return $this->fetch('wf/instance/detail');
    }

    /**
     * 实例详情-内置html表单
     *
     * @return string
     */
    public function detail_idf(): string
    {
        $id          = input('get.id');
        $operate     = input('get.operate', 'detail');
        $instanceUrl = input('get.instance_url');
        $userInfo    = getCurrentUser(true);
        return $this->fetch($instanceUrl, ['id' => $id, 'operate' => $operate, 'nickname' => $userInfo['nickname'] ?? '']);
    }

}
