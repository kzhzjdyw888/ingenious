<?php

namespace app\admin\controller\wf;

use app\admin\controller\Base;
use app\common\api\WorkflowAPI;
use app\common\model\AdminAdmin;
use app\common\util\Json;
use madong\ingenious\enums\ProcessConstEnum;
use think\App;

/**
 * 委托管理
 *
 * @author Mr.April
 * @since  1.0
 */
class Surrogate extends Base
{

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
     */
    public function index(): string
    {
        return $this->fetch('wf/surrogate/index');
    }

    /**
     * 获取列表
     *
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException|\Exception
     */
    public function select(): \think\Response
    {
        $format  = input('format', 'normal');
        $methods = [
            'select'     => 'formatSelect',
            'tree'       => 'formatTree',
            'table_tree' => 'formatTableTree',
            'normal'     => 'formatNormal',
        ];

        $format_function = $methods[$format] ?? 'formatNormal';
        $data            = array_merge($this->request->all(), [ProcessConstEnum::CREATE_USER->value => getCurrentUser()]);
        $result          = $this->service->client('surrogate.list', (object)$data);

        //工作流归档id 将对接id用户追加到列表
        $uids  = array_unique(array_column($result['items'], 'surrogate'));
        $info  = AdminAdmin::whereIn('id', $uids)->select()->toArray();
        $users = [];
        foreach ($info as $value) {
            $users[$value['id']] = $value;
        }
        foreach ($result['items'] as $key => $item) {
            if (isset($users[$item['surrogate']])) {
                $result['items'][$key]['surrogate_real_name'] = $users[$item['surrogate']]['nickname'] ?? '';
            } else {
                $result['items'][$key]['surrogate_real_name'] = '';
            }
        }
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
        $result = $this->service->client('surrogate.findById', $id);
        if ($result) {
            $userId = $result->getData('surrogate');
            if (!empty($userId)) {
                $Admin    = new AdminAdmin();
                $userInfo = $Admin->where('id', $userId)->select()->toArray();
                if (!empty($userInfo)) {
                    $result->set('surrogateData', $userInfo);
                }
            }
            return Json::success('ok', $result->toArray());
        } else {
            return Json::fail('Resource not found');
        }
        return Json::fail('Resource not found');
    }

    /**
     * 插入
     *
     * @return \think\Response|string
     */
    public function insert(): \think\Response|string
    {
        if ($this->request->method() == 'POST') {
            try {
                $data = $this->request->postMore([
                    ['process_define_id', ''],
                    ['operator', getCurrentUser()],
                    ['surrogate'],
                    ['start_time', ''],
                    ['end_time', ''],
                    ['enabled', 1],
                    ['create_user', getCurrentUser()],
                ]);
                if (empty($data['process_define_id'])) {
                    return Json::fail('请选择要委托的流程');
                }
                if (empty($data['surrogate'])) {
                    return Json::fail('代理人不能为空');
                }
                if ($this->service->client('surrogate.created', (object)$data)) {
                    return Json::success('创建成功');
                }
                return Json::fail('网络异常请稍后重试');
            } catch (\Throwable $e) {
                return Json::fail($e->getMessage());
            }

        }
        return $this->fetch('wf/surrogate/insert');
    }

    /**
     * 编辑
     *
     * @return \think\Response|string
     */
    public function update(): \think\Response|string
    {

        if ($this->request->method() == 'POST') {
            try {
                $data = $this->request->postMore([
                    ['id'],
                    ['process_define_id', ''],
                    ['operator', getCurrentUser()],
                    ['surrogate'],
                    ['start_time', ''],
                    ['end_time', ''],
                    ['enabled', 1],
                    ['update_user', getCurrentUser()],
                ]);
                if (empty($data['process_define_id'])) {
                    return Json::fail('请选择要委托的流程');
                }
                if (empty($data['surrogate'])) {
                    return Json::fail('代理人不能为空');
                }
                $result = $this->service->client('surrogate.updated', (object)$data);
                return $result
                    ? Json::success('Update successfully')
                    : Json::fail('Update failure');
            } catch (\Exception $e) {
                return Json::fail($e->getMessage());
            }
        }
        return $this->fetch('wf/surrogate/update');
    }

    /**
     * 删除
     *
     * @return \think\Response
     */
    public function delete(): \think\Response
    {
        try {
            $id   = input('id', []);
            $data = is_array($id) ? $id : explode(',', $id);
            if (empty($data)) {
                throw new \Exception('参数错误：缺少必要的参数（id 或 data）');
            }
            $result = $this->service->client('surrogate.del', $data);
            return Json::success('ok', $result);
        } catch (\Throwable $e) {
            return Json::fail($e->getMessage());
        }
    }
}
