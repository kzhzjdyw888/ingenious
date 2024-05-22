<?php
namespace app\adminapi\controller\setting;

use app\adminapi\controller\AuthController;

use app\common\CacheService;
use app\common\Json;
use app\services\system\config\SystemConfigServices;
use app\services\system\config\SystemConfigTabServices;
use support\Container;
use support\Request;

/**
 * 系统配置
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemConfig extends AuthController
{

    public function __construct()
    {
        parent::__construct();
        $this->services = Container::make(SystemConfigServices::class);
    }

    /**
     * 显示资源列表
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function index(Request $request): \support\Response
    {
        $where = $request->getMore([
            ['tab_id', ''],
            ['status', -1],
            ['info', ''],
        ]);
        if ($where['status'] == -1) {
            unset($where['status']);
        }
        return Json::success($this->services->getConfigList($where));
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \support\Response
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
//    public function create()
//    {
//        [$type, $tabId] = $request->getMore([
//            [['type', 'd'], ''],
//            [['tab_id', 'd'], 1],
//        ], true);
//        return Json::success($this->services->createFormRule($type, $tabId));
//    }

    /**
     * 保存新建的资源
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function save(Request $request): \support\Response
    {
        $data = $request->postMore([
            ['menu_name', ''],
            ['type', ''],
            ['input_type', 'input'],
            ['config_tab_id', 0],
            ['parameter', ''],
            ['upload_type', 1],
            ['required', ''],
            ['width', 0],
            ['high', 0],
            ['value', ''],
            ['info', ''],
            ['desc', ''],
            ['sort', 0],
            ['status', 0],
        ]);
        if (is_array($data['config_tab_id'])) $data['config_tab_id'] = end($data['config_tab_id']);
        if (!$data['info']) return Json::fail(400274);
        if (!$data['menu_name']) return Json::fail(400275);
        if (!$data['desc']) return Json::fail(400276);
        if ($data['sort'] < 0) {
            $data['sort'] = 0;
        }
        if ($data['type'] == 'text') {
            if (!$data['width']) return Json::fail(400277);
            if ($data['width'] <= 0) return Json::fail(400278);
        }
        if ($data['type'] == 'textarea') {
            if (!$data['width']) return Json::fail(400279);
            if (!$data['high']) return Json::fail(400280);
            if ($data['width'] < 0) return Json::fail(400281);
            if ($data['high'] < 0) return Json::fail(400282);
        }
        if ($data['type'] == 'radio' || $data['type'] == 'checkbox') {
            if (!$data['parameter']) return Json::fail(400283);
            $this->services->valiDateRadioAndCheckbox($data);
        }
        $data['value'] = json_encode($data['value']);
        $config        = $this->services->getOne(['menu_name' => $data['menu_name']]);
        if ($config) {
            $this->services->update($config['id'], $data, 'id');
        } else {
            $this->services->save($data);
        }
        CacheService::clear();
        return Json::success(400284);
    }

    /**
     * 显示指定的资源
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function read(Request $request): \support\Response
    {
        $id = $request->input('id');
        if (!$id) {
            return Json::fail(100100);
        }
        $data = $this->services->get($id)->toArray();
        return Json::success($data);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param int $id
     *
     * @return \support\Response
     */
//    public function edit(Request $request): \support\Response
//    {
//        return Json::success($this->services->editConfigForm((int)$id));
//    }

    /**
     * 保存更新的资源
     *
     * @param $id
     *
     * @return \support\Response
     */
    public function update(Request $request): \support\Response
    {
        $type = $request->input('type');
        if ($type == 'text' || $type == 'textarea' || $type == 'radio' || ($type == 'upload' && (request()->post('upload_type') == 1 || request()->post('upload_type') == 3))) {
            $value = request()->post('value');
        } else {
            $value = request()->post('value/a');
        }
        if (!$value) $value = request()->post(request()->post('menu_name'));
        $data = $request->postMore([
            ['menu_name', ''],
            ['type', ''],
            ['input_type', 'input'],
            ['config_tab_id', 0],
            ['parameter', ''],
            ['upload_type', 1],
            ['required', ''],
            ['width', 0],
            ['high', 0],
            ['value', $value],
            ['info', ''],
            ['desc', ''],
            ['sort', 0],
            ['status', 0],
        ]);
        if (is_array($data['config_tab_id'])) $data['config_tab_id'] = end($data['config_tab_id']);
        if (!$this->services->get($id)) {
            return Json::fail(100026);
        }
        $data['value'] = json_encode($data['value']);
        $this->services->update($id, $data);
        CacheService::clear();
        return Json::success(100001);
    }

    /**
     * 删除指定资源
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function delete(Request $request): \support\Response
    {
        $id = $request->input('id');
        if (!$this->services->delete($id))
            return Json::fail(100008);
        else {
            CacheService::clear();
            return Json::success(100002);
        }
    }

    /**
     * 修改状态
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function set_status(Request $request): \support\Response
    {
        $status = $request->input('status');
        $id     = $request->input('id');
        if ($status == '' || $id == 0) {
            return Json::fail(100100);
        }
        $this->services->update($id, ['status' => $status]);
        CacheService::clear();
        return Json::success(100014);
    }

    /**
     * 批量删除
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function batch_delete(Request $request): \support\Response
    {
        $data = $request->post('data', []);
        if (empty($data)) {
            return Json::fail(100100);
        }

        foreach ($data as $key => $value) {
            $this->services->delete($value);
        }
        CacheService::clear();
        return Json::success(100002);
    }

    /**
     * 基础配置
     * */
    public function edit_basics(Request $request): \support\Response
    {
        $tabId = $request->param('tab_id', 1);
        if (!$tabId) {
            return Json::fail(100100);
        }
        $url = $request->baseUrl();
        return Json::success($this->services->getConfigForm($url, $tabId));
    }

    /**
     * 保存数据    true
     * */
//    public function save_basics(Request $request)
//    {
//        $post = $request->post();
//        foreach ($post as $k => $v) {
//            if (is_array($v)) {
//                $res = $this->services->getUploadTypeList($k);
//                foreach ($res as $kk => $vv) {
//                    if ($kk == 'upload') {
//                        if ($vv == 1 || $vv == 3) {
//                            $post[$k] = $v[0];
//                        }
//                    }
//                }
//            }
//        }
//        $this->validate($post, \app\adminapi\validate\setting\SystemConfigValidata::class);
//        if (isset($post['upload_type'])) {
//            $this->services->checkThumbParam($post);
//        }
//        if (isset($post['extract_type']) && !count($post['extract_type'])) {
//            return Json::fail(400753);
//        }
//        if (isset($post['store_brokerage_binding_status'])) {
//            $this->services->checkBrokerageBinding($post);
//        }
//        if (isset($post['store_brokerage_ratio']) && isset($post['store_brokerage_two'])) {
//            $num = $post['store_brokerage_ratio'] + $post['store_brokerage_two'];
//            if ($num > 100) {
//                return Json::fail(400285);
//            }
//        }
//        if (isset($post['spread_banner'])) {
//            $num = count($post['spread_banner']);
//            if ($num > 5) {
//                return Json::fail(400286);
//            }
//        }
//        if (isset($post['user_extract_min_price'])) {
//            if (!preg_match('/[0-9]$/', $post['user_extract_min_price'])) {
//                return Json::fail(400287);
//            }
//        }
//        if (isset($post['wss_open'])) {
//            $this->services->saveSslFilePath((int)$post['wss_open'], $post['wss_local_pk'] ?? '', $post['wss_local_cert'] ?? '');
//        }
//        if (isset($post['store_brokerage_price']) && $post['store_brokerage_statu'] == 3) {
//            if ($post['store_brokerage_price'] === '') {
//                return Json::fail(400288);
//            }
//            if ($post['store_brokerage_price'] < 0) {
//                return Json::fail(400289);
//            }
//        }
//        if (isset($post['store_brokerage_binding_time']) && $post['store_brokerage_binding_status'] == 2) {
//            if (!preg_match("/^[0-9][0-9]*$/", $post['store_brokerage_binding_time'])) {
//                return Json::fail(400290);
//            }
//        }
//        if (isset($post['uni_brokerage_price']) && $post['uni_brokerage_price'] < 0) {
//            return Json::fail(400756);
//        }
//        if (isset($post['day_brokerage_price_upper']) && $post['day_brokerage_price_upper'] < -1) {
//            return Json::fail(400757);
//        }
//        if (isset($post['pay_new_weixin_open']) && (bool)$post['pay_new_weixin_open']) {
//            if (empty($post['pay_new_weixin_mchid'])) {
//                return Json::fail(400763);
//            }
//        }
//        if (isset($post['uni_brokerage_price']) && preg_match('/\.[0-9]{2,}[1-9][0-9]*$/', (string)$post['uni_brokerage_price']) > 0) {
//            return Json::fail(500029);
//        }
//
//        if (isset($post['weixin_ckeck_file'])) {
//            $from = public_path() . $post['weixin_ckeck_file'];
//            $to   = public_path() . array_reverse(explode('/', $post['weixin_ckeck_file']))[0];
//            @copy($from, $to);
//        }
//        if (isset($post['ico_path'])) {
//            $from     = public_path() . $post['ico_path'];
//            $toAdmin  = public_path('admin') . 'favicon.ico';
//            $toHome   = public_path('home') . 'favicon.ico';
//            $toPublic = public_path() . 'favicon.ico';
//            @copy($from, $toAdmin);
//            @copy($from, $toHome);
//            @copy($from, $toPublic);
//        }
//        if (isset($post['reward_integral']) || isset($post['reward_money'])) {
//            if ($post['reward_money'] < 0) return Json::fail('赠送余额不能小于0元');
//            if ($post['reward_integral'] < 0) return Json::fail('赠送积分不能小于0');
//        }
//
//        if (isset($post['sign_give_point'])) {
//            if (!is_int($post['sign_give_point']) || $post['sign_give_point'] < 0) return Json::fail('签到赠送积分请填写大于等于0的整数');
//        }
//        if (isset($post['sign_give_exp'])) {
//            if (!is_int($post['sign_give_exp']) || $post['sign_give_exp'] < 0) return Json::fail('签到赠送经验请填写大于等于0的整数');
//        }
//        if (isset($post['integral_frozen'])) {
//            if (!ctype_digit($post['integral_frozen']) || $post['integral_frozen'] < 0) return Json::fail('积分冻结天数请填写大于等于0的整数');
//        }
//        if (isset($post['store_free_postage'])) {
//            if (!is_int($post['store_free_postage']) || $post['store_free_postage'] < 0) return Json::fail('满额包邮请填写大于等于0的整数');
//        }
//        if (isset($post['withdrawal_fee'])) {
//            if ($post['withdrawal_fee'] < 0 || $post['withdrawal_fee'] > 100) return Json::fail('提现手续费范围在0-100之间');
//        }
//        if (isset($post['routine_auth_type']) && count($post['routine_auth_type']) == 0) return Json::fail('微信和手机号登录开关至少开启一个');
//        if (isset($post['integral_max_num'])) {
//            if (!ctype_digit($post['integral_max_num']) || $post['integral_max_num'] < 0) return Json::fail('积分抵扣上限请填写大于等于0的整数');
//        }
//        if (isset($post['customer_phone'])) {
//            if (!ctype_digit($post['customer_phone']) || strlen($post['customer_phone']) > 11) return Json::fail('客服手机号为11位数字');
//        }
//
//        foreach ($post as $k => $v) {
//            $config_one = $this->services->getOne(['menu_name' => $k]);
//            if ($config_one) {
//                $config_one['value'] = $v;
//                $this->services->valiDateValue($config_one);
//                $this->services->update($k, ['value' => json_encode($v)], 'menu_name');
//            }
//        }
//        CacheService::clear();
//        return Json::success(100001);
//
//    }

    /**
     * 获取系统设置头部分类
     *
     * @return \support\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function header_basics(Request $request): \support\Response
    {
        [$type, $pid] = $request->getMore([
            [['type', 'd'], 0],
            [['pid', 'd'], 0],
        ], true);
        if ($type == 3) {//其它分类
            $config_tab = [];
        } else {
            $services   = Container::make(SystemConfigTabServices::class);
            $config_tab = $services->getConfigTab($pid);
            if (empty($config_tab)) $config_tab[] = $services->get($pid, ['id', 'id as value', 'title as label', 'pid', 'icon', 'type']);
        }
        return Json::success(compact('config_tab'));
    }

    /**
     * 获取单个配置的值
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function get_system(Request $request): \support\Response
    {
        $name  = $request->input('name');
        $value = sys_config($name);
        return Json::success(compact('value'));
    }

    /**
     * 获取某个分类下的所有配置
     *
     * @param $tabId
     *
     * @return \support\Response
     */
    public function get_config_list(Request $request): \support\Response
    {
        $tabId = $request->input('tabId');
        $list  = $this->services->getConfigTabAllList($tabId);
        $data  = [];
        foreach ($list as $item) {
            $data[$item['menu_name']] = json_decode($item['value']);
        }
        return Json::success($data);
    }

}
