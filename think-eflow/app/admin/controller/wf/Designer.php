<?php

namespace app\admin\controller\wf;

use app\admin\controller\Base;
use app\admin\controller\wf\trait\DesignTrait;
use app\common\api\WorkflowAPI;
use app\common\model\AdminAdmin;
use app\common\model\AdminRole;
use app\common\util\Json;
use madong\ingenious\enums\ProcessConstEnum;
use think\App;

/**
 * 流程设计
 *
 * @author Mr.April
 * @since  1.0
 */
class Designer extends Base
{

    use  DesignTrait;

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
        return $this->fetch('wf/designer/index');
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
        $result          = $this->service->client('design.list', (object)$this->request->all());
        return call_user_func([$this, $format_function], $result['items'], $result['total']);
    }

    /**
     * 详情
     *
     * @return \think\Response
     */
    public function show(): \think\Response
    {
        try {
            $id     = input('get.id');
            $result = $this->service->client('design.findById', $id);
            return $result
                ? Json::success('ok', $result->toArray())
                : Json::fail('Not Found');
        } catch (\Throwable $e) {
            return Json::fail($e->getMessage());
        }
    }

    /**
     * 插入
     *
     * @return \think\Response|string
     */
    public function insert(): \think\Response|string
    {
        if ($this->request->method() === 'POST') {
            try {
                $data = $this->request->postMore([
                    ['name', ''],
                    ['display_name', ''],
                    ['description', ''],
                    ['type_id', ''],
                    ['icon', ''],
                    ['remark', ''],
                    ['create_user', getCurrentUser()],
                ]);
                if (empty($data['type_id'])) {
                    return $this->fail('请选择流程类型');
                }
                if (empty($data['name'])) {
                    return $this->fail('唯一编码不能为空');
                }
                if (empty($data['display_name'])) {
                    return $this->fail('显示名称不能为空');
                }

                $result = $this->service->client('design.created', (object)$data);
                return Json::success('ok', [$result->getData($result->getPk())]);
            } catch (\Throwable $e) {
                return Json::fail($e->getMessage());
            }
        }
        return $this->fetch('wf/designer/insert');
    }

    /**
     * 更新
     *
     * @return \think\Response
     */
    public function update(): \think\Response|string
    {
        if ($this->request->method() === 'POST') {
            try {
                $data = $this->request->postMore([
                    ['id'],
                    ['name', ''],
                    ['display_name', ''],
                    ['description', ''],
                    ['type_id', ''],
                    ['icon', ''],
                    ['remark', ''],
                    ['create_user', getCurrentUser()],
                ]);
                if (empty($data['type_id'])) {
                    return Json::fail('请选择流程类型');
                }
                if (empty($data['name'])) {
                    return Json::fail('唯一编码不能为空');
                }
                if (empty($data['display_name'])) {
                    return Json::fail('显示名称不能为空');
                }
                $result = $this->service->client('design.updated', (object)$data);
                return $result
                    ? Json::success('更新成功')
                    : Json::fail('更新失败');
            } catch (\Exception $e) {
                return Json::fail($e->getMessage());
            }
        }
        return $this->fetch('wf/designer/update');
    }

    /**
     * 删除
     *
     * @return \think\Response
     */
    public function delete(): \think\Response
    {
        try {
            $data = (array)input('id', []);
            if (empty($data)) {
                throw new \Exception('参数错误：缺少必要的参数（id 或 data）');
            }
            $result = $this->service->client('design.del', $data);
            return Json::success('ok', $result);
        } catch (\Throwable $e) {
            return Json::fail($e->getMessage());
        }
    }

    /**
     * 部署流程
     *
     * @return \think\Response
     */
    public function deploy(): \think\Response
    {
        try {
            $id = input('id', null);
            $this->service->client('design.deploy', $id, getCurrentUser());
            return Json::success('保存成功');
        } catch (\Throwable $e) {
            return Json::fail($e->getMessage());
        }
    }

    /**
     * 重新部署
     *
     * @return \think\Response
     */
    public function redeploy(): \think\Response
    {
        try {
            $id = input('id', null);
            $this->service->client('design.redeploy', $id, getCurrentUser());
            return Json::success('保存成功');
        } catch (\Throwable $e) {
            return Json::fail($e->getMessage());
        }
    }

    /**
     * design
     *
     * @return string
     * @throws \Exception
     */
    public function design(): string
    {
        $id          = input('get.id');
        $result      = $this->service->client('design.findById', $id);
        $latestModel = $result->latest_history ?? null;
        $graphData   = (object)[];
        if (!empty($latestModel)) {
            $content = $latestModel->getData('content');
            if (!empty($content)) {
                $graphData = is_string($content) ? json_decode($content) : $content;
            }
        }
        if (!empty($result)) {
            $graphData->name         = $result->getData('name');//追加key
            $graphData->display_name = $result->getData('display_name');//追加显示名称
        }
        $data = [
            'viewer'        => false,
            'graphData'     => $graphData,
            'highLight'     => [],
            'commitPath'    => '/admin/wf.designer/updateDefine',
            'defaultConfig' => (object)['grid' => true],
        ];
        return $this->fetch('wf/common/design/index', ['data' => json_encode($data)]);
    }

    /**
     * 保存设计
     *
     * @return \think\Response
     */
    public function updateDefine(): \think\Response
    {
        try {
            $data                                                 = input('graph_data', []);
            $data[ProcessConstEnum::CREATE_USER->value]           = getCurrentUser();//追加用户id
            $data[ProcessConstEnum::PROCESS_DESIGN_ID_KEY->value] = input('process_design_id');
//            if (!$this->validate->scene('update_define')->check($data)) {
//                return Json::fail($this->validate->getError());
//            }
            $this->service->client('design.updateDefine', (object)$data);
            return Json::success('保存成功');
        } catch (\Throwable $e) {
            return Json::fail($e->getMessage());
        }
    }

    /**
     * 参与者列表
     *
     * @return \think\Response
     * @throws \think\db\exception\DbException
     */
    public function assignee(): \think\Response
    {
        $param = $this->request->all();
        // 构建查询条件
        $query = AdminAdmin::whereNotNull('id');
        if (!empty($param['name'])) {
            $query->where('nickname', 'like', $param['name'] . '%');
        }
        if (!empty($param['id'])) {
            $map = is_string($param['id']) ? explode(',', $param['id']) : $param['id'];
            $query->whereIn('id', $map); // 确保 $map 是数组
        }
        // 获取记录总数
        $data['total'] = $query->count();
        // 获取分页数据
        $data['items'] = $query->paginate((int)$param['limit'])->items();
        return Json::success($data);
    }

    /**
     * 用户组列表获取
     *
     * @return \think\Response
     * @throws \think\db\exception\DbException
     */
    public function group(): \think\Response
    {
        $param = $this->request->all();
        // 构建查询条件
        $query = AdminRole::whereNotNull('id');
        if (!empty($param['name'])) {
            $query->where('name', 'like', $param['name'] . '%');
        }
        if (!empty($param['id'])) {
            $query->whereIn('id', (array)$param['id']); // 确保 $param['id'] 是数组
        }
        // 获取记录总数
        $data['total'] = $query->count();
        // 获取分页数据
        $data['items'] = $query->paginate((int)$param['limit'])->items();
        return Json::success($data);

    }

    /**
     * 参与者处理类
     *
     * @return \think\Response
     */
    public function assigneeHandler(): \think\Response
    {
        //替换自己注入的实际类
        $data = [];
        return Json::success($data);
    }

    /**
     * 表单列表option
     *
     * @return \think\Response
     * @throws \Exception
     */
    public function formOptions(): \think\Response
    {
        $type = input('instance_type', 1);
        switch ($type) {
            case 2:
                //内置表单
                $data = config('form', []);
                break;
            default:
                //动态表单
                $result = $this->service->client('form.list', (object)['page' => 1, 'limit' => 99999]);
                $data   = [];
                foreach ($result['items'] as $item) {
                    $data[] = ['value' => $item['name'], 'label' => $item['display_name']];
                }
                break;
        }
        return Json::success($data);
    }

    /**
     * 重新格式化下拉列表
     *
     * @param $items
     *
     * @return \think\Response
     */
    protected function formatSelect($items): \think\Response
    {
        $formatted_items = [];
        foreach ($items as $item) {
            $formatted_items[] = [
                'label' => $item->display_name,
                'value' => $item->id,
            ];
        }
        return Json::success('ok', $formatted_items);
    }
}
