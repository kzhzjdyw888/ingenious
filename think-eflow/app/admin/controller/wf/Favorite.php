<?php

namespace app\admin\controller\wf;

use app\admin\controller\Base;
use app\common\api\WorkflowAPI;
use app\common\util\Json;
use think\App;

/**
 * 我的收藏
 *
 * @author Mr.April
 * @since  1.0
 */
class Favorite extends Base
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
        return $this->fetch('wf/favorite/index');
    }

    /**
     * 列表
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
        $result          = $this->service->client('define_fav.list', (object)$this->request->all());
        return call_user_func([$this, $format_function], $result['items'], $result['total']);
    }

    /**
     * 添加收藏
     *
     * @return \think\Response|string
     */
    public function insert(): \think\Response|string
    {
        try {
            if (!$this->request->isAjax()) {
                return $this->fetch('wf/favorite/insert');
            }
            $data            = $this->request->all();
            $data['user_id'] = getCurrentUser();
            $result          = $this->service->client('define_fav.created', (object)$data);
            return Json::success('ok', [$result->getData($result->getPk())]);
        } catch (\Exception $e) {
            return Json::fail($e->getMessage());
        }
    }

    /**
     * 移除收藏
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
            $result = $this->service->client('define_fav.del', $data);
            return Json::success('ok', $result);
        } catch (\Throwable $e) {
            return Json::fail($e->getMessage());
        }
    }

}
