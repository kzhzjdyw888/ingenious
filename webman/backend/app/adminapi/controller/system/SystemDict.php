<?php


namespace app\adminapi\controller\system;

use app\adminapi\controller\AuthController;
use app\common\Json;
use app\services\system\dict\SystemDictServices;
use support\Container;
use support\Request;
use think\facade\Queue;

class SystemDict extends AuthController
{

    /**
     * 构造方法
     * SystemLog constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->services = Container::make(SystemDictServices::class);
    }

    /**
     * index
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function index(Request $request): \support\Response
    {
        $where = $request->getMore([['name', '']]);
        return Json::success($this->services->getDictList($where));
    }

    /**
     * 添加字典
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function save(Request $request): \support\Response
    {
        $data = $request->postMore([
            ['name', ''],
            ['value', ''],
        ]);

        $message = [
            'name' => '400220',
        ];
        $this->validate($data, [
            'name' => 'require',
        ], $message, true);
        if ($this->services->saveDict($data['name'], $data['value'])) {
            return Json::success(100000);
        } else {
            return Json::fail(100006);
        }
    }

    /**
     * 获取详情
     *
     * @param \support\Request $request
     *
     * @return mixed
     */
    public function read(Request $request): \support\Response
    {
        $id = $request->input('id');
        if (empty($id)) {
            return Json::fail(100100);
        }
        return Json::success($this->services->read($id));
    }

    /**
     * 更新
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function update(Request $request): \support\Response
    {
        $id = $request->input('id');
        if (empty($id)) {
            return Json::fail(100100);
        }
        $data = $request->postMore([
            ['name', ''],
            ['value', ''],
        ]);

        $message = [
            'name' => '400220',
        ];
        $this->validate($data, [
            'name' => 'require',
        ], $message, true);

        if ($this->services->updateDict($id, $data)) {
            return Json::success(100000);
        } else {
            return Json::fail(100006);
        }

    }

    /**
     * 删除字典
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function delete(Request $request): \support\Response
    {
        $id = $request->input('id');
        if (empty($id)) {
            return Json::fail(100100);
        }
        if ($this->services->delete($id, 'id')) {
            return Json::success(100002);
        } else {
            return Json::fail(100008);
        }
    }

    /**
     * 批量删除
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function batchDelete(Request $request): \support\Response
    {
        $data = $request->input('data', []);
        if (empty($data)) {
            return Json::fail(100100);
        }
        foreach ($data as $value) {
            $this->services->delete($value, 'id');
        }
        return Json::success(100002);
    }

    /**
     * 获取字典详情
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function get(Request $request): \support\Response
    {
        $name          = $request->input('name');
        $data          = $this->services->getName($this->services->dictNameToOptionName($name));
        $data['value'] = json_decode($data['value']);
        return Json::success($data);
    }

}
